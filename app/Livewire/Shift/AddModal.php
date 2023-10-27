<?php

namespace App\Livewire\Shift;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models\Shift;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

		$this->dispatch('pg:eventRefresh-default');
	}

    protected function rules()
	{
        return [
			'employee' => 'required',
			'worksite' => 'required',
			'date' => 'required|date',
			'startTime' => 'required',
			'endTime' => 'required',
			'isExtraordinary' => 'required|boolean',
			'note' => 'nullable|string',
			'isExtraordinary' => 'required|boolean'
        ];
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
}
