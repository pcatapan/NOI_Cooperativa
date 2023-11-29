<?php

namespace App\Livewire\Report;

use WireUi\Traits\Actions;
use App\Enums\UserRoleEnum;
use App\Models\Company;
use App\Models\Presence;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;

class CompanyDetails extends ModalComponent
{
    use Actions;

	public Company $company;
	public Collection $presences;
	public $name;
	
	public $from_date = null;
	public $to_date = null;

	protected static array $maxWidths = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-md md:max-w-lg',
        'xl' => 'sm:max-w-md md:max-w-xl',
        '2xl' => 'sm:max-w-md md:max-w-xl lg:max-w-2xl',
        '3xl' => 'sm:max-w-2xl md:max-w-2xl lg:max-w-3xl',
        '4xl' => 'sm:max-w-3xl md:max-w-3xl lg:max-w-3xl xl:max-w-4xl',
        '5xl' => 'sm:max-w-5xl md:max-w-5xl lg:max-w-5xl xl:max-w-5xl !overflow-scroll',
        '6xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-6xl',
        '7xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-7xl',
    ];

	public static function modalMaxWidth(): string
	{
		return '3xl';
	}

	public function mount(Company $company, $from_date = null, $to_date = null)
    {
		$this->company = $company;
		$this->name = $company->name;
		$this->from_date = $from_date ? Carbon::parse($from_date) : null;
		$this->to_date = $to_date ? Carbon::parse($to_date) : null;

		$query = Presence::where('absent', false)
			->with('worksite')
			->whereHas('worksite', function ($query) {
				$query->where('id_company', $this->company->id);
			})
			->when($this->from_date, fn($query) => $query->whereDate('date', '>=', $this->from_date))
			->when($this->to_date, fn($query) => $query->whereDate('date', '<=', $this->to_date))
		;

		$this->presences = $query->get()->map(function ($presence) {
			return $this->transformPresence($presence);
		});
    }

	private function transformPresence($presence)
	{
		$interval = CarbonInterval::minutes($presence->minutes_worked ?: $presence->minutes_extraordinary);
		$worked = $interval->cascade()->forHumans();

		return [
			'worksite' => $presence->worksite->cod,
			'employee' => $presence->employee->full_name,
			'date' => $presence->date->format('d/m/Y'),
			'start' => $presence->time_entry_extraordinary ?: $presence->time_entry,
			'end' => $presence->time_exit_extraordinary ?: $presence->time_exit,
			'worked' => $worked,
			'extraordinary' => $presence->time_entry_extraordinary ? 'straordinario' : 'normale'
		];
	}

	public function render()
    {
        return view('livewire.report.company-details');
    }

	public function cancel()
    {
        $this->closeModal();
    }
}