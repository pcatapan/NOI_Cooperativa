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
	public ?int $company = null;
	public ?int $employee = null;

	public bool $notShowEmployee;

	public function mount($notShowEmployee = false)
	{
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
			'employee' => $this->employee,
		]);

		$this->dispatch('pg:eventRefresh-default');
	}
}