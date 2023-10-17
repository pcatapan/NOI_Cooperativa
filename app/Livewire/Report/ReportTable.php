<?php

namespace App\Livewire\Report;

use App\Models\Presence;
use Illuminate\Support\Carbon;
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

final class ReportTable extends PowerGridComponent
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
            ->leftjoin('users', 'presences.id_user', '=', 'users.id')
            ->leftjoin('worksites', 'presences.id_worksite', '=', 'worksites.id')
            ->leftjoin('companies', 'worksites.id_company', '=', 'companies.id')
            ->select(
                'presences.*',
                'users.surname as user_surname',
                'worksites.cod as worksite_cod',
                'companies.name as company_name',
                DB::raw('SUM(hours_worked) as total_hours_worked'),
                DB::raw('SUM(hours_extraordinary) as total_hours_extraordinary')
            )
            ->groupBy('presences.id_user', 'presences.date')
            ->orderBy('date', 'desc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('user_surname', function (Presence $model) {
                return e($model->user->surname);
            })
            ->addColumn('company' , function (Presence $model) {
				return e($model->worksite->company->name);
			})
            ->addColumn('worksite_cod' , function (Presence $model) {
				return e($model->worksite->cod);
			})
            ->addColumn('date_formatted', function (Presence $model) {
                return e(Carbon::parse($model->date)->format('d/m/Y'));
            })
            ->addColumn('total_hours_worked', function (Presence $model) {
                return e($model->total_hours_worked);
            })
            ->addColumn('total_hours_extraordinary', function (Presence $model) {
                return e($model->total_hours_extraordinary);
            })
            ->addColumn('action', function (Presence $model) {
                return view('livewire.report.action', ['model' => $model]);
            })
        ;
    }

    public function columns(): array
    {
        return [
            Column::make('Cognome', 'user_surname')
                ->sortable()
                ->searchable(),

            Column::make('Azienda', 'company')
                ->sortable()
                ->searchable(),

            Column::make('Cantiere', 'worksite_cod')
                ->sortable()
                ->searchable(),

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

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(\App\Models\Presence $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
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
