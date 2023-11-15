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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;

final class ReportEmployeeTable extends PowerGridComponent
{
    use WithExport;

	public bool $multiSort = true;
    protected $listeners = ['updateSerach' => 'updateSerach'];

    public $from_date = null;
    public $to_date = null;
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
        $this->company = $filters['company'];
        $this->employee = $filters['employee'];
    }

    public function datasource(): Builder
    {
        $query = Presence::query()
            ->leftjoin('employees', 'presences.id_employee', '=', 'employees.id')
            ->leftjoin('users', 'employees.id_user', '=', 'users.id')
            ->leftjoin('worksites', 'presences.id_worksite', '=', 'worksites.id')
            ->leftjoin('companies', 'worksites.id_company', '=', 'companies.id')
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
            ->orderBy('users.name', 'desc');

        // Applica il filtro per la data iniziale se impostato
        if (isset($this->from_date)) {
            $query->whereDate('presences.date', '>=', $this->from_date);
        }

        // Applica il filtro per la data finale se impostato
        if (isset($this->to_date)) {
            $query->whereDate('presences.date', '<=', $this->to_date);
        }

        if (isset($this->company)) {
            $query->where('companies.id', $this->company);
        }

        if (isset($this->employee)) {
            $query->where('employees.id', $this->employee);
        }

        return $query;
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
            Column::action(__('general.action')),

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
                    'from_date'		=> $this->from_date,
                    'to_date'		=> $this->to_date,
                    'company'		=> $this->company,
			]),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
