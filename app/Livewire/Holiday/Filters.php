<?php

namespace App\Livewire\Holiday;

use Livewire\Component;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;

class Filters extends Component
{
	public ?int $worksite = null;

	public bool $isResponsible;
	public ?int $userId;

	public function mount()
	{
		$this->userId = Auth::user()->getEmployeeId();
		$this->isResponsible = Auth::user()->role == UserRoleEnum::RESPONSIBLE->value;
	}

	public function render()
	{
		return view('livewire.holiday.filters');
	}

	public function search()
	{
		// In `report.filters` component
		$this->dispatch('updateSerach', [
			'worksite' => $this->worksite,
		]);

		$this->dispatch('pg:eventRefresh-default');
	}
}