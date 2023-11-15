<?php

namespace App\Livewire\Worksite;

use App\Models\Worksite;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use App\Enums\UserRoleEnum;
use App\Models\Company;
use Illuminate\Support\Str;

final class WorksiteTable extends PowerGridComponent
{
    use WithExport;

	public bool $multiSort = true;

    #[On('create')]
    public function create(): void
    {
        redirect()->route('worksite.add_edit');
    }
    public function setUp(): array
    {
        if (Auth::user()->role !== UserRoleEnum::ADMIN->value) {
			abort(403, __('general.403'));
		}

		//$this->persist(['columns', 'filters']);

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

    public function header(): array
    {
        return [
            Button::add('create')
                ->slot(Str::ucfirst(__('worksite.create')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('create', []),

        ];
    }

    public function datasource(): Builder
    {
        return Worksite::query()
            ->leftjoin('companies', function ($companies) {
                $companies->on('worksites.id_company', '=', 'companies.id');
            })
            ->leftjoin('employees', function ($users) {
                $users->on('worksites.id_responsable', '=', 'employees.id');
            })
            ->leftjoin('users', function ($users) {
                $users->on('employees.id_user', '=', 'users.id');
            })
            ->select([
                'worksites.*',
                'companies.name as company',
                'users.surname as user_surname',
                'users.name as user_name',
            ])
         ->addSelect(DB::raw("CONCAT(users.name, ' ', users.surname) as user_name_surname"))
        ;
    }

    public function relationSearch(): array
    {
        return [
            'company' => [
                'companies.name',
            ],
            'employee' => [
                'users.name',
                'users.surname'
            ],
        ];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('description')
            ->addColumn('cod')
            ->addColumn('company', fn (Worksite $worksite) => $worksite->company ?? '-')
            ->addColumn('user_surname')
            ->addColumn('user_name')
            ->addColumn('user_name_surname')
            ->addColumn('total_hours')
            ->addColumn('total_hours_extraordinary')
        ;
    }

    public function columns(): array
    {
        return [
            Column::action(__('general.action')),

            Column::make(__('worksite.cod'), 'cod')
                ->sortable()
                ->searchable(),

            Column::make(__('worksite.description'), 'description')
                ->searchable(),

            Column::make(__('worksite.company'), 'company', 'companies.name')
                ->sortable()
                ->searchable(),

            Column::make('Cognome', 'user_surname', 'users.surname')
                ->hidden()
                ->searchable(),
            
            Column::make('Nome', 'user_name', 'users.name')
                ->hidden()
                ->searchable(),

            Column::make(__('worksite.responsible'), 'user_name_surname')
                ->sortable()
                ->searchable(),

            Column::make(__('worksite.total_hours'), 'total_hours')
                ->sortable(),

            Column::make(__('worksite.total_hours_extraordinary'), 'total_hours_extraordinary')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('company', 'companies.name')
                ->dataSource(Company::all())
                ->optionValue('name')
                ->optionLabel('name'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
	public function edit($worksite): void
	{
        redirect()->route('worksite.add_edit', ['worksite' => $worksite]);
	}
    public function actions(\App\Models\Worksite $row): array
    {
        return [
            Button::add('edit')
				->slot(Str::ucfirst(__('general.edit')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->dispatch('edit', ['worksite' => $row->id]),

            Button::add('delete')
				->slot(Str::ucfirst(__('general.delete')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-red-600 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-red-700')
				->openModal('delete-modal', [
					'confirmationTitle'       => __('general.delete_confirmation_title'),
                    'confirmationDescription' => __('general.delete_confirmation_description'),
                    'id'                  => $row->id,
					'ids'					=> [],
					'class'					=> Employee::class,
                ]),
        ];
    }

    public function actionRules($row): array
    {
       return [
            Rule::button('edit')
                ->when(fn () => Auth::user()->role !== UserRoleEnum::ADMIN->value)
                ->hide(),

            Rule::button('delete')
				->when(fn () => Auth::user()->role !== UserRoleEnum::ADMIN->value)
				->hide(),
        ];
    }
}
