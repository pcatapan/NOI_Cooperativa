<?php

namespace App\Livewire\Shift;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models\Shift;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EditModal extends ModalComponent
{
    use Actions;

    public $shift;

	public $date;
	public $start;
	public $end;
	public bool $isExtraordinary;
	public ?string $note;

    public function mount(Shift $shift)
    {
        $this->shift = $shift;
		$this->date = $shift->date;
		$this->start = $shift->start;
		$this->end = $shift->end;
		$this->isExtraordinary = $shift->is_extraordinary;
		$this->note = $shift->note;
    }

    public function render()
    {
        return view('livewire.shift.edit');
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

		try {
			$this->shift->date = $this->date;
			$this->shift->start = $this->start;
			$this->shift->end = $this->end;
			$this->shift->is_extraordinary = $this->isExtraordinary;
			$this->shift->note = $this->note ?? null;
	
			$this->shift->save();
		} catch (\Exception $e) {
			$this->showErrorNotification();
			return;
		}

        $this->closeModalWithEvents(['pg:eventRefresh-default']);
        $this->showSuccessNotification();
    }

    protected function rules()
	{
        return [
			'date' => 'required|date',
			'start' => 'required',
			'end' => 'required',
			'isExtraordinary' => 'required|boolean',
			'note' => 'nullable|string',
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
