<?php

namespace App\Livewire\Report;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models\Employee;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;

class Details extends ModalComponent
{
    use Actions;

	public Employee $employee;
	public Collection $presences;
	public $name;
	public $date;

	public function mount(Employee $employee, $date)
    {
		$this->employee = $employee;
		$this->name = $employee->user->name . ' ' . $employee->user->surname;
		$this->date = $date;

		// Recupero tutte le presenze di un dipendete in una determinata data
		$this->presences = $this->employee->presences()
			->where('absent', false)
			->whereDate('date', $this->date)
			->get()
			->map(function ($presence) {
				if($presence->time_entry_extraordinary) {
					$interval = CarbonInterval::minutes($presence->minutes_extraordinary);
					$worked = $interval->cascade()->forHumans();
					return array(
						'worksite' => $presence->worksite->cod,
						'start' => $presence->time_entry_extraordinary,
						'end' => $presence->time_exit_extraordinary,
						'worked' => $worked,
						'extraordinary' => 'straordinario'
					);
				}

				$interval = CarbonInterval::minutes($presence->minutes_worked);
				$worked = $interval->cascade()->forHumans();
				return array(
					'worksite' => $presence->worksite->cod,
					'start' => $presence->time_entry,
					'end' => $presence->time_exit,
					'worked' => $worked,
					'extraordinary' => 'normale'
				);
			})
		;

    }

	public function render()
    {
        return view('livewire.report.details');
    }

	public function cancel()
    {
        $this->closeModal();
    }

	public static function modalMaxWidth(): string
	{
		return 'xl';
	}
}