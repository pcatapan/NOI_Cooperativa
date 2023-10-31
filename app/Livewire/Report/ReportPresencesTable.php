<?php

namespace App\Livewire\Report;

use App\Models\Presence;
use Illuminate\Support\Carbon;
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
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;

final class ReportPresencesTable extends PowerGridComponent
{
    use WithExport;

	public bool $multiSort = true;

    public function setUp(): array
    {
        if (Auth::user()->role !== UserRoleEnum::ADMIN->value) {
			abort(403, __('general.403'));
		}

		$this->persist(['columns', 'filters']);
        //$this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            Header::make()
                ->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        // Faccio la sommo di tutte le presenza di un determinato mese
        return Presence::query()
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
            ->groupBy('presences.id_employee', 'presences.date')
            ->where('absent', false)
            ->orderBy('date', 'desc');
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
            ->addColumn('date_formatted', function (Presence $model) {
                return e(Carbon::parse($model->date)->format('d/m/Y'));
            })
            ->addColumn('total_hours_worked', function (Presence $model) {
                $interval = CarbonInterval::minutes($model->total_hours_worked);
                return $interval->cascade()->forHumans();
            })
            ->addColumn('total_hours_extraordinary', function (Presence $model) {
                $extraordinaryHours = $model->total_hours_extraordinary;
                if ($extraordinaryHours == 0) {
                    return "0 ore";
                } else {
                    $interval = CarbonInterval::minutes($extraordinaryHours);
                    return $interval->cascade()->forHumans();
                }
            })
            ->addColumn('action')
        ;
    }

    public function columns(): array
    {
        return [
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

            Column::make('Date', 'date_formatted', 'date')
                ->sortable(),

            Column::make('Ore Lavorate', 'total_hours_worked')
                ->sortable(),

            Column::make('Ore Straordinarie', 'total_hours_extraordinary')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date'),
        ];
    }

    public function actions(\App\Models\Presence $row): array
    {
        return [
            Button::add('details')
				->slot(Str::ucfirst(__('general.details')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->openModal('report.details', [
					'employee'	=> $row->id_employee,
                    'date' => $row->date->format('Y-m-d'),
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
