<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Enums\PresesenceTypeEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models;

class Responsible extends Component
{
	public $worksiteOverLimitOrdinary;
	public int $worksiteOverLimitOrdinaryCount;
	public int $shiftValidated;
	public int $shiftNotValidated;

	public $worksiteOverLimitExtraordinary;
	public int $worksiteOverLimitExtraordinaryCount;

	public bool $openModalOrdinary = false;
	public bool $openModalExtraordinary = false;

	public string $name;
	public array $tableShifts;
	public ?int $id = null;
	public ?int $userEmployee = null;

	public bool $createShiftModal = false;

	public ?int $employee = null;
	public ?int $worksite = null;
	public $date = null;
	public $startTime = null;
	public $endTime = null;
	public ?string $note = null;
	public bool $isExtraordinary = false;
	public $error = null;

	public $selectedNote;

	public function mount()
	{
		$user = Auth::user();
		$this->name = $user->name . ' ' . $user->surname;
		$this->tableShifts = $this->createTabelShifts();
		$this->id = $user->id;
		$this->userEmployee = $user->getEmployeeId();

		$this->shiftValidated = Models\Shift::where('validated', 1)
			->where('id_employee', $this->userEmployee)
			->count()
		;
		$this->shiftNotValidated = Models\Shift::where('validated', 0)
			->where('date', '<', Carbon::now())
			->where('id_employee', $this->userEmployee)
			->count()
		;

		$startOfMonth = Carbon::today()->startOfMonth();
		$today = Carbon::today();
		$numberOfweek = $today->diffInWeeks($startOfMonth);
		$this->worksiteOverLimitOrdinary = DB::table('presences')
			->join('worksites', 'presences.id_worksite', '=', 'worksites.id')
			->select(
				DB::raw('ROUND(SUM(presences.minutes_worked) / 60) as total_hours_worked'),
				'worksites.*'
			)
			->where('presences.type', PresesenceTypeEnum::ORDINARY->value)
			->where('worksites.id_responsable', $this->userEmployee)
			->whereBetween('presences.date', [$startOfMonth, $today])
			->groupBy('presences.id_worksite')
			->havingRaw('total_hours_worked * ? > worksites.total_hours', [$numberOfweek])
			->get()
		;
		$this->worksiteOverLimitOrdinaryCount = $this->worksiteOverLimitOrdinary->count();

		$this->worksiteOverLimitExtraordinary = Models\Presence::join('worksites', 'presences.id_worksite', '=', 'worksites.id')
			->select(
				'presences.id_worksite',
				DB::raw('ROUND(SUM(presences.minutes_worked) / 60) as total_hours_worked'),
				'worksites.*'
			)
			->where('presences.type', PresesenceTypeEnum::EXTRAORDINARY->value)
			->where('worksites.id_responsable', $this->userEmployee)
			->whereBetween('presences.date', [$startOfMonth, $today])
			->groupBy('presences.id_worksite', 'worksites.total_hours')
			->havingRaw('total_hours_worked * ? > worksites.total_hours', [$numberOfweek])
			->get();
		;
		$this->worksiteOverLimitExtraordinaryCount = $this->worksiteOverLimitExtraordinary->count();
	}

	public function render()
	{
		return view('livewire.dashboard.responsible');
	}

	protected function rules()
	{
		$rules = [
			'employee' => 'required',
			'worksite' => 'required',
			'date' => 'required',
			'startTime' => 'required',
			'endTime' => 'required',
			'note' => 'nullable|string',
			'isExtraordinary' => 'required|boolean'
		];

		$worksite = Models\Worksite::find($this->worksite);
		$dateStartWeek = Carbon::now()->startOfWeek();
		$worksiteHoursWorked = Models\Presence::where('id_worksite', $this->worksite)
			->whereBetween('date', [$dateStartWeek, Carbon::now()])
			->sum('minutes_worked') / 60
		;
		$newWorksiteHoursWorked = $worksiteHoursWorked + (Carbon::parse($this->startTime)->diffInMinutes($this->endTime) / 60);
		if ($worksite && (($newWorksiteHoursWorked > $worksite->total_hours) || ($newWorksiteHoursWorked > $worksite->total_hours_extraordinary && $this->isExtraordinary))) {
			session()->flash('error', 'Le ore lavorative superano il limite consentito per questo cantiere.');
			
			$rules['error'] = 'required';
		}

		return $rules;
	}

	public function createShift()
	{
		$this->validate();

		$shift = new Shift();
		$shift->id_employee = $this->employee;
		$shift->id_worksite = $this->worksite;
		$shift->date = $this->date;
		$shift->start = $this->startTime;
		$shift->end = $this->endTime;
		$shift->is_extraordinary = $this->isExtraordinary;
		$shift->note = $this->note;

		$shift->save();

		$this->showSuccessNotification();

		$this->createShiftModal = false;

		$this->dispatch('pg:eventRefresh-default');
	}

	public function showNote($note)
    {
        $this->selectedNote = $note;
        $this->dispatchBrowserEvent('show-modal');  // scatena l'apertura della modal
    }

	public function openModalCreateShift()
	{
		$this->createShiftModal = true;
	}

	protected function createTabelShifts()
	{
		$responsible = Auth::user()->employee;
		$worksites = $responsible->worksites;

		$data = [];

		foreach ($worksites as $worksite) {
			$data[$worksite] = $worksite->shifts()->pluck('name', 'id');
		}

		return $data;
	}

	protected function showErrorNotification()
	{
		session()->flash('error', Str::ucfirst(__('general.save_error_title')));
	}

	protected function showSuccessNotification()
	{
		session()->flash('success', Str::ucfirst(__('general.save_success_title')));
	}

	public function toggleModalOrdinary()
	{
		$this->openModalOrdinary = !$this->openModalOrdinary;
	}

	public function toggleModalExtraordinary()
	{
		$this->openModalExtraordinary = !$this->openModalExtraordinary;
	}
}