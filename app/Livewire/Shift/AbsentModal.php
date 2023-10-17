<?php

namespace App\Livewire\Shift;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Enums\PresenceAbsentTypeEnum;
use App\Models\User;
use App\Models\Shift;
use App\Models\Presence;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;

class AbsentModal extends ModalComponent
{
    use Actions;

    public $shift;
	public User $user;

    public ?string $typeAbsent = null;
    public ?string $note = null;

    public function mount(Shift $shift)
    {
        $this->shift = $shift;
		$this->user = $shift->employee->user;
    }

    public function render()
    {
        return view('livewire.shift.absent');
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

        $this->validate();

        $this->shift->validated = true;
		$this->shift->save();

        $presence = new Presence();
        $presence->id_employee = $this->shift->id_employee;
        $presence->id_worksite = $this->shift->id_worksite;
        $presence->id_shift = $this->shift->id;
        $presence->time_entry = $this->shift->start;
        $presence->time_exit = $this->shift->end;
        $presence->date = $this->shift->date;
        $presence->note = $this->note;
        $presence->absent = true;
        $presence->type_absent = $this->typeAbsent;
        $presence->hours_worked = 0;

        $presence->save();

        $this->closeModalWithEvents(['pg:eventRefresh-default']);
        $this->showSuccessNotification();
    }

    protected function rules()
	{
        return [
            'typeAbsent' => 'required|in:' . implode(',', PresenceAbsentTypeEnum::getValues()),
            'note' => 'nullable|string|max:255',
        ];
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
