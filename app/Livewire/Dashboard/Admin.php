<?php

namespace App\Livewire\Dashboard;

use App\Enums\PresesenceTypeEnum;
use Livewire\Component;
use App\Models;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Admin extends Component
{
	public string $name;

	public int $numberEmployees;
	public int $numberCompanies;
	public int $numberWorksites;
	public int $shiftValidated;
	public int $shiftNotValidated;

	public $worksiteOverLimitOrdinary;
	public int $worksiteOverLimitOrdinaryCount;

	public $worksiteOverLimitExtraordinary;
	public int $worksiteOverLimitExtraordinaryCount;

	public bool $openModalOrdinary = false;
	public bool $openModalExtraordinary = false;

	public function mount()
	{
		$this->name = Auth::user()->name;
		$this->numberEmployees = Models\Employee::where('active', 1)->count();
		$this->numberCompanies = Models\Company::count();
		$this->numberWorksites = Models\Worksite::count();
		$this->shiftValidated = Models\Shift::where('validated', 1)->count();
		$this->shiftNotValidated = Models\Shift::where('validated', 0)->where('date', '<', Carbon::now())->count();

		$startOfMonth = Carbon::today()->startOfMonth();
		$today = Carbon::today();
		$numberOfweek = $today->diffInWeeks($startOfMonth);
		$this->worksiteOverLimitOrdinary = DB::table('presences')
			->join('worksites', 'presences.id_worksite', '=', 'worksites.id')
			->select(
				DB::raw('ROUND(SUM(presences.minutes_worked) / 60) as total_hours_worked'),
				'worksites.*'
			)
			->where('presences.type', PresesenceTypeEnum::ORDINARY->value)
			->whereBetween('presences.date', [$startOfMonth, $today])
			->groupBy('presences.id_worksite')
			->havingRaw('total_hours_worked * ? > worksites.total_hours', [$numberOfweek])
			->get()
		;
		$this->worksiteOverLimitOrdinaryCount = $this->worksiteOverLimitOrdinary->count();

		$this->worksiteOverLimitExtraordinary = Models\Presence::join('worksites', 'presences.id_worksite', '=', 'worksites.id')
			->select(
				'presences.id_worksite',
				DB::raw('ROUND(SUM(presences.minutes_worked) / 60) as total_hours_worked'),
				'worksites.*'
			)
			->where('presences.type', PresesenceTypeEnum::EXTRAORDINARY->value)
			->whereBetween('presences.date', [$startOfMonth, $today])
			->groupBy('presences.id_worksite', 'worksites.total_hours')
			->havingRaw('total_hours_worked * ? > worksites.total_hours', [$numberOfweek])
			->get();
		;
		$this->worksiteOverLimitExtraordinaryCount = $this->worksiteOverLimitExtraordinary->count();
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

	public function toggleModalOrdinary()
	{
		$this->openModalOrdinary = !$this->openModalOrdinary;
	}

	public function toggleModalExtraordinary()
	{
		$this->openModalExtraordinary = !$this->openModalExtraordinary;
	}
}