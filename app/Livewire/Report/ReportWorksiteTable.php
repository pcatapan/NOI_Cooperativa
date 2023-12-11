<?php

namespace App\Livewire\Report;

use App\Models\Presence;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;

final class ReportWorksiteTable extends PowerGridComponent
{
    use WithExport;

	public bool $multiSort = true;
    protected $listeners = ['updateSerach' => 'updateSerach'];

    public $from_date = null;
    public $to_date = null;
    public ?int $company = null;
    public int $weeksCount = 1;

    public function setUp(): array
    {
        if (Auth::user()->role === UserRoleEnum::EMPLOYEE->value) {
			abort(403, __('general.403'));
		}
        //$this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            Header::make(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function updateSerach($filters)
    {
        $this->from_date = $filters['from_date'] ? Carbon::parse($filters['from_date']) : null;
        $this->to_date = $filters['to_date'] ? Carbon::parse($filters['to_date']) : null;
        $this->company = $filters['company'];
    }

    public function datasource(): Builder
    {
        return Presence::query()
            ->leftjoin('employees', 'presences.id_employee', '=', 'employees.id')
            ->leftjoin('users', 'employees.id_user', '=', 'users.id')
            ->leftjoin('worksites', 'presences.id_worksite', '=', 'worksites.id')
            ->leftjoin('companies', 'worksites.id_company', '=', 'companies.id')
            ->select(
                'presences.*',
                'worksites.cod as worksite_name',
                'worksites.total_hours as worksite_total_hours',
                'worksites.total_hours_extraordinary as worksite_total_hours_extraordinary',
                DB::raw('SUM(minutes_worked) as total_hours_worked'),
                DB::raw('SUM(minutes_extraordinary) as total_hours_extraordinary')
            )
            ->when($this->company, function ($query, $company) {
                return $query->where('companies.id', $company);
            })
            ->when($this->from_date || $this->to_date, function ($query) {
                return $query->where('presences.date', '>=', $this->from_date)
                    ->where('presences.date', '<=', $this->to_date);
            })
            ->when(Auth::user()->role == UserRoleEnum::RESPONSIBLE->value, function ($query) {
                return $query->where('worksites.id_responsable', Auth::user()->employee->id);
            })
            ->groupBy('presences.id_worksite')
            ->where('absent', false)
            ->orderBy('users.name', 'desc')
        ;
    }


    public function relationSearch(): array
    {
        return [
            //
        ];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id_worksite')
            ->addColumn('worksite_name')
            ->addColumn('total_hours_worked', function (Presence $model) {
                // Imposta un'istanza di Carbon a mezzanotte
                $startOfDay = Carbon::now()->startOfDay();

                // Aggiungi i minuti lavorati a quella istanza per ottenere la nuova ora
                $endOfDayWithMinutesWorked = $startOfDay->copy()->addMinutes($model->total_hours_worked);

                // Calcola la differenza in ore tra l'inizio della giornata e il nuovo orario
                $hoursWorked = $startOfDay->diffInHours($endOfDayWithMinutesWorked, false);

                return $hoursWorked . ' ore';
            })
            ->addColumn('worksite_total_hours', function (Presence $model) {
                // Calcola il numero di settimane nel range date
                $this->weeksCount = $this->from_date && $this->to_date
                    ? Carbon::parse($this->from_date)->diffInWeeks(Carbon::parse($this->to_date))
                    : 1 // se non è specificato nessun intervallo, assumi 1 settimana
                ;
                
                return ($model->worksite_total_hours * $this->weeksCount) . ' ore';
            })
            ->addColumn('total_hours_extraordinary', function (Presence $model) {
                // Imposta un'istanza di Carbon a mezzanotte
                $startOfDay = Carbon::now()->startOfDay();

                // Aggiungi i minuti lavorati a quella istanza per ottenere la nuova ora
                $endOfDayWithMinutesWorked = $startOfDay->copy()->addMinutes($model->total_hours_extraordinary);

                // Calcola la differenza in ore tra l'inizio della giornata e il nuovo orario
                $hoursWorked = $startOfDay->diffInHours($endOfDayWithMinutesWorked, false);

                return $hoursWorked . ' ore';
            })
            ->addColumn('worksite_total_hours_extraordinary', function (Presence $model) {
                // Calcola il numero di settimane nel range date
                $this->weeksCount = $this->from_date && $this->to_date
                    ? Carbon::parse($this->from_date)->diffInWeeks(Carbon::parse($this->to_date))
                    : 1 // se non è specificato nessun intervallo, assumi 1 settimana
                ;
                
                return ($model->worksite_total_hours_extraordinary * $this->weeksCount) . ' ore';
            })
            ->addColumn('action')
        ;
    }

    public function columns(): array
    {
        return [
            Column::action(__('general.action')),

            Column::make('ID', 'id_worksite')
                ->hidden(),
            
            Column::make(__('report.worksite'), 'worksite_name')
                ->sortable(),

            Column::make(__('report.hours_worked'), 'total_hours_worked')
                ->sortable(),

            Column::make(__('report.total_hours_available'), 'worksite_total_hours')
                ->sortable(),

            Column::make(__('report.hours_extraordinary'), 'total_hours_extraordinary')
                ->sortable(),

            Column::make(__('report.total_hours_available'), 'worksite_total_hours_extraordinary')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            //
        ];
    }

    public function actions(\App\Models\Presence $row): array
    {
        return [
            Button::add('details')
				->slot(Str::ucfirst(__('general.details')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->openModal('report.worksite-details', [
					'worksite'	    => $row->id_worksite,
                    'from_date'		=> $this->from_date,
                    'to_date'		=> $this->to_date,
                    'company'		=> $this->company,
			]),

            Button::make('alert_ordinary', '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>')
                ->class('items-center flex justify-center h-full text-red-500')
                ->tooltip(__('report.over_limit')),

            Button::make('alert_extraordinary', '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>')
                ->class('items-center flex justify-center h-full text-red-500')
                ->tooltip(__('report.over_limit'))
        ];
    }

    public function actionRules($row): array
    {
       return [
            Rule::button('alert_ordinary')
                ->when(fn($row) => ($row->total_hours_worked / 60) < ($row->worksite_total_hours * $this->weeksCount))
                ->hide(),

            Rule::button('alert_extraordinary')
                ->when(fn($row) => ($row->total_hours_extraordinary / 60) <= ($row->worksite_total_hours_extraordinary * $this->weeksCount))
                ->hide(),
        ];
    }
}
