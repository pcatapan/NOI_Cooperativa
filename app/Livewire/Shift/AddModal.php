<?php

namespace App\Livewire\Shift;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;

class AddModal extends ModalComponent
{
    use Actions;

	public ?int $id = null;
	public ?int $userEmployee = null;

	public ?int $employee = null;
	public ?int $worksite = null;
	public $date = null;
	public $startTime = null;
	public $endTime = null;
	public ?string $note = null;
	public bool $isExtraordinary = false;

	public bool $removeOverflow = true; 

    public function mount($worksite)
    {
		$this->worksite = $worksite;
		$this->id = Auth::user()->id;
		$this->userEmployee = Auth::user()->getEmployeeId();
    }

    public function render()
    {
        return view('livewire.shift.add');
    }

    public function cancel()
    {
        $this->closeModal();
    }

    public function store()
	{
		$this->validate();

		$shift = new Models\Shift();
		$shift->id_employee = $this->employee;
		$shift->id_worksite = $this->worksite;
		$shift->date = $this->date;
		$shift->start = $this->startTime;
		$shift->end = $this->endTime;
		$shift->is_extraordinary = $this->isExtraordinary;
		$shift->note = $this->note;

		$shift->save();

		$this->showSuccessNotification();

		$this->dispatch('pg:eventRefresh-default');
	}

	public function createShift()
	{
		$this->validate();

		$shift = new Models\Shift();
		$shift->id_employee = $this->employee;
		$shift->id_worksite = $this->worksite;
		$shift->date = $this->date;
		$shift->start = $this->startTime;
		$shift->end = $this->endTime;
		$shift->is_extraordinary = $this->isExtraordinary;
		$shift->note = $this->note;

		$shift->save();

		$this->showSuccessNotification();

		$this->closeModal();

		$this->dispatch('pg:eventRefresh-default');
	}

    protected function rules()
	{
        $rules = [
			'employee' => 'required',
			'worksite' => 'required',
			'date' => 'required|date',
			'startTime' => 'required',
			'endTime' => 'required',
			'isExtraordinary' => 'required|boolean',
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
			
			return [];
		}

		return $rules;
    }

    protected function shouldNotAllowDeletion()
    {
        return Auth::user()->role === UserRoleEnum::EMPLOYEE->value;
    }

    protected function showSuccessNotification()
	{
		$this->notification([
            'title'       => Str::ucfirst(__('general.save_success_title')),
            'icon'        => 'success',
			'timeout'     => 2000,
			'closeButton' => false,
        ]);
	}

	protected function showErrorNotification()
	{
		$this->notification([
			'title'       => Str::ucfirst(__('general.save_error_title')),
			'icon'        => 'error',
			'timeout'     => 2000,
			'closeButton' => false,
		]);
	}

	public static function modalMaxWidth(): string
	{
		return 'xl';
	}
}
