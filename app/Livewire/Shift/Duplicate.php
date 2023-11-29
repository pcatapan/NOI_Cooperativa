<?php

namespace App\Livewire\Shift;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models\Shift;
use Carbon\Carbon;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;

class Duplicate extends ModalComponent
{
    use Actions;

    public $shiftToDuplicate;

	public Carbon $fromDate;
	public Carbon $toDate;

    public function mount(Array $shifts)
    {
		$this->shiftToDuplicate = $shifts;
		$this->fromDate = Carbon::tomorrow();
		$this->toDate = Carbon::tomorrow()->addDays(7);
        
    }

    public function render()
    {
        return view('livewire.shift.duplicate');
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

		$shiftToDuplicate = collect($this->shiftToDuplicate)->map(function ($shift) {
			return Shift::find($shift);
		});

		try {
			foreach ($this->fromDate->daysUntil($this->toDate) as $date) {
				foreach ($shiftToDuplicate as $shift) {
					$newShift = $shift->replicate(['id', 'created_at', 'updated_at']);

					$newShift->date = $date->format('Y-m-d');
					$newShift->save();
				}
			}
	
			$this->closeModalWithEvents(['pg:eventRefresh-default']);
			$this->showSuccessNotification();
		} catch (\Exception $e) {
			// Log dell'eccezione e visualizzazione di un messaggio di errore
			Log::error("Errore durante la duplicazione dello shift: {$e->getMessage()}");
			$this->showErrorNotification();
		}

        $this->closeModalWithEvents(['pg:eventRefresh-default']);
        $this->showSuccessNotification();
    }

    protected function rules()
	{
		return [
			'fromDate' => ['required', 'date', 'after_or_equal:today'],
			'toDate' => ['required', 'date', 'after_or_equal:fromDate'],
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
