<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Filters extends Component
{
	public $from_date = null;
	public $to_date = null;
	public ?int $worksite = null;
	public ?int $company = null;
	public ?int $employee = null;

	public bool $notShowEmployee;
	public bool $isResponsible;
	public int $userId;

	public function mount($notShowEmployee = false)
	{
		$this->userId = Auth::user()->getEmployeeId();
		$this->isResponsible = Auth::user()->role == UserRoleEnum::RESPONSIBLE->value;
		$this->notShowEmployee = $notShowEmployee;
	}

	public function render()
	{
		return view('livewire.report.filters');
	}

	public function search()
	{
		// In `report.filters` component
		$this->dispatch('updateSerach', [
			'from_date' => $this->from_date,
			'to_date' => $this->to_date,
			'company' => $this->company,
			'worksite' => $this->worksite,
			'employee' => $this->employee,
		]);

		$this->dispatch('pg:eventRefresh-default');
	}
}