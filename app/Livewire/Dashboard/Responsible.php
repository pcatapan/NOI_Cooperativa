<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Shift;
use App\Enums\UserRoleEnum;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class Responsible extends Component
{
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

	public $selectedNote;

	public function mount()
	{
		$user = Auth::user();
		$this->name = $user->name . ' ' . $user->surname;
		$this->tableShifts = $this->createTabelShifts();
		$this->id = $user->id;
		$this->userEmployee = $user->getEmployeeId();
	}

	public function render()
	{
		return view('livewire.dashboard.responsible');
	}

	protected function rules()
	{
		return [
			'employee' => 'required',
			'worksite' => 'required',
			'date' => 'required',
			'startTime' => 'required',
			'endTime' => 'required',
			'note' => 'nullable|string',
			'isExtraordinary' => 'required|boolean'
		];
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

	protected function showSuccessNotification()
	{
		session()->flash('message', __('general.save_success_title'));
	}
}