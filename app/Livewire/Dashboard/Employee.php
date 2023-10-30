<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Shift;
use App\Enums\UserRoleEnum;
use App\Http\Controllers\WorksiteController;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class Employee extends Component
{
	public string $name;

	public array $events = [];

	public function mount()
	{
		$user = Auth::user();
		$this->name = $user->name . ' ' . $user->surname;

		// Prendo tutti i turni dell'utente
		$shifts = Shift::where('id_employee', $user->employee->id)
			->get();
		;

		foreach ($shifts as $shift) {
			$startTime = $shift->date->clone();
			$endTime = $shift->date->clone();

			list($hours, $minutes, $seconds) = explode(':', $shift->start);
			$startTime->setTime($hours, $minutes, $seconds);

			list($hours, $minutes, $seconds) = explode(':', $shift->end);
			$endTime->setTime($hours, $minutes, $seconds);

			$this->events[] = [
				'id' => $shift->id,
				'title' => $shift->worksite->cod,
				'start' => $startTime->toIso8601String(),
				'end' => $endTime->toIso8601String(),
			];
		}
	}

	public function render()
	{
		return view('livewire.dashboard.employee');
	}
}