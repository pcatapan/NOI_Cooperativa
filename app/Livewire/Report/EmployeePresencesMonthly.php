<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Enums\UserRoleEnum;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class EmployeePresencesMonthly extends Component
{
	public Employee $employee;

	public string $monthTranslate;
	public $now;
	public Collection $presences;

	public function mount()
	{
		if (Auth::user()->role != UserRoleEnum::EMPLOYEE->value) {
			abort(403, 'Unauthorized action.');
		}

		$this->employee = Auth::user()->employee;

		// Mese corrente
		$this->now = Carbon::now();
		$this->monthTranslate = $this->now->translatedFormat('F');

		$this->presences = self::getPresencesByMonth();
	}

	public function previousMonth()
	{
		$this->now = $this->now->subMonth();
		$this->monthTranslate = $this->now->translatedFormat('F');

		$this->presences = self::getPresencesByMonth();
	}
	
	public function nextMonth()
	{
		$this->now = $this->now->addMonth();
		$this->monthTranslate = $this->now->translatedFormat('F');

		$this->presences = self::getPresencesByMonth();
	}

	protected function getPresencesByMonth()
	{
		return $this->employee->presences()
			->whereMonth('date', $this->now->month)
			->where('absent', false)
			->orderBy('date', 'asc')
			->get()
		;
	}

	public function render()
	{
		return view('livewire.report.employee-presences-monthly');
	}
}