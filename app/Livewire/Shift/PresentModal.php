<?php

namespace App\Livewire\Shift;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use App\Enums\UserRoleEnum;
use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
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

        //creo presenza
        $start = Carbon::createFromFormat('H:i:s', $this->shift->start);
        $end = Carbon::createFromFormat('H:i:s', $this->shift->end);
        $presence = new Presence();
        $presence->id_shift = $this->shift->id;
        $presence->id_employee = $this->shift->id_employee;
        $presence->id_worksite = $this->shift->id_worksite;
        $presence->date = $this->shift->date;
        if ($this->shift->is_extraordinary) {
            $presence->time_entry_extraordinary = $start;
            $presence->time_exit_extraordinary = $end;
            $presence->hours_extraordinary = $start->diffInMinutes($end);
            $presence->motivation_extraordinary = $this->shift->note;
        } else {
            $presence->time_entry = $start;
            $presence->time_exit = $end;
            $presence->hours_worked = $start->diffInMinutes($end);
            $presence->note = $this->shift->note;
        }
        $presence->absent = false;

        $presence->save();

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
