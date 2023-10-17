<?php

namespace App\Livewire\Shift;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use App\Enums\UserRoleEnum;
use App\Models\User;
use WireUi\Traits\Actions;

class PresentModal extends ModalComponent
{
    use Actions;

    public $shift;
	public User $user;

    public function mount(Shift $shift)
    {
        $this->shift = $shift;
		$this->user = $shift->employee->user;
    }

    public function render()
    {
        return view('livewire.shift.present');
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

        $this->shift->validated = true;
		$this->shift->save();

        $this->closeModalWithEvents(['pg:eventRefresh-default']);
        $this->showSuccessNotification();
    }

    protected function shouldNotAllowDeletion()
    {
        return Auth::user()->role === UserRoleEnum::EMPLOYEE->value;
    }

    protected function showSuccessNotification()
	{
		session()->flash('message', __('general.save_success_title'));
	}
}
