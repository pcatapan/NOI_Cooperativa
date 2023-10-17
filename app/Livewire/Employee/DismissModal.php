<?php

namespace App\Livewire\Employee;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRoleEnum;
use App\Models\Employee;
use WireUi\Traits\Actions;

class DismissModal extends ModalComponent
{
    use Actions;

    public $confirmationTitle;
    public $confirmationDescription;
	public Employee $employee;

    public function mount(string $confirmationTitle, string $confirmationDescription, $id)
    {
		$this->employee = Employee::find($id);
        $this->confirmationTitle = $confirmationTitle;
        $this->confirmationDescription = $confirmationDescription;
    }

    public function render()
    {
        return view('livewire.employee.dismiss');
    }

    public function cancel()
    {
        $this->closeModal();
    }

    public function confirm()
    {
        if ($this->shouldNotAllowDeletion()) {
            abort(403, __('general.403'));
        }

        $this->employee->update([
			'active' => false,
			'date_of_resignation' => now(),
		]);

        $this->showSuccessNotification();
        $this->closeModalWithEvents(['pg:eventRefresh-default']);
    }

    protected function shouldNotAllowDeletion()
    {
        return Auth::user()->role !== UserRoleEnum::ADMIN->value;
    }

    protected function showSuccessNotification()
    {
        $this->notification([
            'title' => __('employee.dismiss_success_title'),
            'icon' => 'success',
            'timeout' => 1300,
        ]);
    }
}
