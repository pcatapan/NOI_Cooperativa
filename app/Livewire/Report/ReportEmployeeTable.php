<?php

namespace App\Livewire\Report;

use App\Models\Presence;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support as Support;
use PowerComponents\LivewirePowerGrid\DataSource\Builder as BuilderExport;
use PowerComponents\LivewirePowerGrid\ProcessDataSource;
use Illuminate\Database\Eloquent as Eloquent;
use App\Http\Services\UtilsServices;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRoleEnum;
use App\Models\Employee;
use Illuminate\Support\Str;

final class ReportEmployeeTable extends PowerGridComponent
{
    use WithExport;

	public bool $multiSort = true;
    protected $listeners = ['updateSerach' => 'updateSerach'];

    public $from_date = null;
    public $to_date = null;
    public ?int $worksite = null;
    public ?int $company = null;
    public ?int $employee = null;

    public function setUp(): array
    {
        if (Auth::user()->role !== UserRoleEnum::ADMIN->value) {
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
        $this->worksite = $filters['worksite'];
        $this->company = $filters['company'];
        $this->employee = $filters['employee'];
    }

    public function datasource(): Builder
    {
        return Presence::query()
            ->leftjoin('employees', 'presences.id_employee', '=', 'employees.id')
            ->leftjoin('users', 'employees.id_user', '=', 'users.id')
            ->leftjoin('worksites', 'presences.id_worksite', '=', 'worksites.id')
            ->leftjoin('companies', 'worksites.id_company', '=', 'companies.id')
            ->when($this->worksite, function ($query, $worksite) {
                return $query->where('worksites.id', $worksite);
            })
            ->when($this->company, function ($query, $company) {
                return $query->where('companies.id', $company);
            })
            ->when($this->employee, function ($query, $employee) {
                return $query->where('employees.id', $employee);
            })
            ->when($this->from_date, function ($query) {
                return $query->where('presences.date', '>=', $this->from_date);
            })
            ->when($this->to_date, function ($query) {
                return $query->where('presences.date', '<=', $this->to_date);
            })
            ->select(
                'presences.*',
                'users.surname as user_surname',
                'users.name as user_name',
                DB::raw('SUM(minutes_worked) as total_hours_worked'),
                DB::raw('SUM(minutes_extraordinary) as total_hours_extraordinary')
            )
            ->addSelect(DB::raw("CONCAT(users.name, ' ', users.surname) as user_name_surname"))
            ->groupBy('presences.id_employee')
            ->where('absent', false)
            ->orderBy('users.name', 'desc')
        ;
    }


    public function relationSearch(): array
    {
        return [
            'employee' => [
                'users.name',
                'users.surname'
            ],
        ];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id_employee')
            ->addColumn('user_surname')
            ->addColumn('user_name')
            ->addColumn('user_name_surname')
            ->addColumn('total_hours_worked', function (Presence $model) {
                // Imposta un'istanza di Carbon a mezzanotte
                $startOfDay = Carbon::now()->startOfDay();

                // Aggiungi i minuti lavorati a quella istanza per ottenere la nuova ora
                $endOfDayWithMinutesWorked = $startOfDay->copy()->addMinutes($model->total_hours_worked);

                // Calcola la differenza in ore tra l'inizio della giornata e il nuovo orario
                $hoursWorked = $startOfDay->diffInHours($endOfDayWithMinutesWorked, false);

                return $hoursWorked . ' ore';
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
            ->addColumn('action')
        ;
    }

    public function columns(): array
    {
        return [
            Column::action(__('general.action'))
                ->visibleInExport(false),

            Column::make('ID', 'id_employee')
                ->hidden(),
            
            Column::make('Cognome', 'user_surname', 'users.surname')
                ->hidden()
                ->searchable(),
            
            Column::make('Nome', 'user_name', 'users.name')
                ->hidden()
                ->searchable(),
            
            Column::make('Dipendente', 'user_name_surname')
                ->sortable(),

            Column::make('Ore Lavorate', 'total_hours_worked')
                ->sortable(),

            Column::make('Ore Straordinarie', 'total_hours_extraordinary')
                ->sortable(),

            Column::make(__('report.details'), 'additional_data')
                ->hidden()
                ->visibleInExport(true),
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
				->openModal('report.employee-details', [
					'employee'	    => $row->id_employee,
                    'worksite'	    => $this->worksite,
                    'from_date'		=> $this->from_date,
                    'to_date'		=> $this->to_date,
                    'company'		=> $this->company,
			]),
        ];
    }

    public function prepareToExport(bool $selected = false) : Eloquent\Collection|Support\Collection {
        $processDataSource = tap(ProcessDataSource::fillData($this), fn ($datasource) => $datasource->get());

        $inClause = $processDataSource->component->filtered;

        if ($selected && filled($processDataSource->component->checkboxValues)) {
            $inClause = $processDataSource->component->checkboxValues;
        }

        if ($processDataSource->isCollection) {
            if ($inClause) {
                $results = $processDataSource->get()->whereIn($this->primaryKey, $inClause);

                return $processDataSource->transform($results);
            }

            return $processDataSource->transform($processDataSource->resolveCollection());
        }

        /** @phpstan-ignore-next-line */
        $currentTable = $processDataSource->component->currentTable;

        $sortField = Support\Str::of($processDataSource->component->sortField)->contains('.') ? $processDataSource->component->sortField : $currentTable . '.' . $processDataSource->component->sortField;

        $results = $processDataSource->prepareDataSource()
            ->where(
                fn ($query) => BuilderExport::make($query, $this)
                    ->filterContains()
                    ->filter()
            )
            ->when($inClause, function ($query, $inClause) use ($processDataSource) {
                return $query->whereIn($processDataSource->component->primaryKey, $inClause);
            })
            ->orderBy($sortField, $processDataSource->component->sortDirection)
            ->get()
        ;

        foreach ($results as $result) {
            $result->additional_data = $this->getAdditionalData($result->id_employee);
        }

        return $processDataSource->transform($results);
    }

    private function getAdditionalData($employeeId)
    {
        $details = [];

        $employee = Employee::find($employeeId);
		$query = UtilsServices::getDetailsReportEmployee($employee, $this->worksite, $this->from_date, $this->to_date, $this->company);
        $query = $query->get();

        $details = $query->map(function ($item) {

            $interval = CarbonInterval::minutes($item->minutes_worked ?: $item->minutes_extraordinary);
		    $worked = $interval->cascade()->forHumans();

            return [
                'worksite' => $item->worksite->cod,
                'date' => $item->date->format('d/m/Y'),
                'start' => $item->time_entry_extraordinary ?: $item->time_entry,
                'end' => $item->time_exit_extraordinary ?: $item->time_exit,
                'worked' => $worked,
                'extraordinary' => $item->time_entry_extraordinary ? 'straordinario' : 'normale',
                'holiday' => UtilsServices::isHoliday($item->worksite, $item->date),
            ];
        });

        return $details;
    }
}
