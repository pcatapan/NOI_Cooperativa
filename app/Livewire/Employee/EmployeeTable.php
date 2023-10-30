<?php

namespace App\Livewire\Employee;

use App\Enums\UserRoleEnum;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
use Illuminate\Support\Str;

final class EmployeeTable extends PowerGridComponent
{
	use WithExport;

	public bool $multiSort = true;

	#[On('create')]
    public function create(): void
    {
        redirect()->route('employee.add_edit');
    }

	public function setUp(): array
	{
		if (Auth::user()->role !== UserRoleEnum::ADMIN->value) {
			abort(403, __('general.403'));
		}
		$this->persist(['columns', 'filters']);
		
		return [

			Exportable::make('export')
				->striped()
				->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

			Header::make()
				->showSearchInput()
				->showToggleColumns(),

			Footer::make()
				->showPerPage()
				->showRecordCount(),
		];
	}

	public function header(): array
    {
        return [
            Button::add('create')
                ->slot(Str::ucfirst(__('employee.employee_create')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('create', []),
        ];
    }

	public function datasource(): Builder
	{
		return Employee::query()
			->join('users', function ($users) { 
				$users->on('users.id', '=', 'employees.id_user');
			})
			->leftjoin('companies', function ($companies) { 
				$companies->on('companies.id', '=', 'users.id_company');
			})
			->select([
				'employees.*',
				'companies.name as company',
				'users.surname as surname',
				'users.role as role',
			]);
		;
	}

	public function relationSearch(): array
	{
		return [
			'user' => [
				'surname',
				'companies.name',
				'role'
			],
		];
	}

	public function addColumns(): PowerGridColumns
	{
		return PowerGrid::columns()
			->addColumn('surname', function (Employee $model) {
				return e($model->user->surname);
			})
			->addColumn('role', function (Employee $model) {
				return e(UserRoleEnum::from($model->user->role)->labels());
			})
			->addColumn('company' , function (Employee $model) {
				return e($model->company ?? '-');
			})
			->addColumn('number_serial')
			->addColumn('fiscal_code')
			->addColumn('inps_number')
			->addColumn('address')
			->addColumn('city')
			->addColumn('province')
			->addColumn('phone', fn (Employee $model) => $model->phone ? '<a href="tel:' . $model->phone . '">' . $model->phone . '</a>' : '-')
			->addColumn('notes')
			->addColumn('date_of_hiring_formatted', fn (Employee $model) => Carbon::parse($model->date_of_hiring)->format('d/m/Y'))
			->addColumn('date_of_resignation_formatted', fn (Employee $model) => $model->date_of_resignation ? Carbon::parse($model->date_of_resignation)->format('d/m/Y') : '-')
			->addColumn('job')
			->addColumn('active');
	}

	public function columns(): array
	{
		return [
			Column::action(__('general.action')),

			Column::make(__('employee.active'), 'active')
				->toggleable(),

			Column::make(__('employee.surname'), 'surname', 'users.surname')
				->sortable()
				->searchable(),
			
			Column::make(__('employee.role'), 'role', 'users.role')
				->sortable(),

			Column::make(__('employee.number_serial'), 'number_serial')
				->sortable()
				->searchable(),

			Column::make(__('employee.fiscal_code'), 'fiscal_code')
				->sortable()
				->searchable(),

			Column::make(__('employee.inps_number'), 'inps_number')
				->sortable()
				->searchable(),
			
			Column::make(__('employee.company'), 'company', 'companies.name')
				->placeholder(Str::ucfirst(__('employee.company')))
				->sortable()
				->searchable(),

			Column::make(__('employee.address'), 'address')
				->hidden()
				->sortable(),

			Column::make(__('employee.city'), 'city')
				->hidden()
				->sortable()
				->searchable(),

			Column::make(__('employee.province'), 'province')
				->hidden()
				->sortable(),

			Column::make(__('employee.phone'), 'phone')
				->searchable(),

			Column::make(__('employee.notes'), 'notes')
				->hidden(),

			Column::make(__('employee.date_of_hiring'), 'date_of_hiring_formatted', 'date_of_hiring')
				->sortable(),

			Column::make(__('employee.date_of_resignation'), 'date_of_resignation_formatted', 'date_of_resignation')
				->sortable(),

			Column::make(__('employee.job'), 'job')
				->sortable()
				->searchable(),
		];
	}

	public function filters(): array
	{
		return [
			Filter::inputText('company', 'companies.name')
				->operators(['contains']),
			Filter::datepicker('date_of_hiring'),
			Filter::datepicker('date_of_resignation'),
			Filter::boolean('active')->label(__('general.yes'), __('general.no')),
		];
	}

	public function onUpdatedToggleable(string $id, string $field, string $value): void
	{
		Employee::query()->find($id)->update([
			$field => $value,
		]);
	}

	#[\Livewire\Attributes\On('edit')]
	public function edit($employee): void
	{
        redirect()->route('employee.add_edit', ['employee' => $employee]);
	}

	public function actions(\App\Models\Employee $row): array
	{
		return [
			Button::add('edit')
				->slot(Str::ucfirst(__('general.edit')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->dispatch('edit', ['employee' => $row->id]),
		];
	}

	public function actionRules($row): array
	{
	   return [
		Rule::button('edit')
				->when(fn () => Auth::user()->role !== UserRoleEnum::ADMIN->value)
				->hide(),

		//Rule::button('delete')
		//		->when(fn () => Auth::user()->role !== UserRoleEnum::ADMIN->value)
		//		->hide(),
		];
	}
}
