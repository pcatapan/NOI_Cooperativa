<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Admin extends Component
{
	public string $name;

	public int $numberEmployees;
	public int $numberCompanies;
	public int $numberWorksites;

	public function mount()
	{
		$this->name = Auth::user()->name;
		$this->numberEmployees = Models\Employee::where('active', 1)->count();
		$this->numberCompanies = Models\Company::count();
		$this->numberWorksites = Models\Worksite::count();
		
	}

	public function render()
	{
		return view('livewire.dashboard.admin');
	}

	protected function rules()
	{
		return [
			//
		];
	}
}