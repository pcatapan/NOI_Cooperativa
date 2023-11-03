<?php

namespace App\Livewire\Shift;

use App\Models\Shift;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;

final class ShiftTodayTable extends PowerGridComponent
{
	use WithExport;

	public bool $multiSort = true;

	public function setUp(): array
	{
		if (Auth::user()->role === UserRoleEnum::EMPLOYEE->value) {
			abort(403, __('general.403'));
		}

		$this->persist(['columns', 'filters']);

		//$this->showCheckBox();

		return [
			Exportable::make('export')
				->striped()
				->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

			Header::make()->showSearchInput(),

			Footer::make()
				->showPerPage()
				->showRecordCount(),
		];
	}

	public function datasource(): Builder
	{
		return Shift::query()
			->leftjoin('employees', 'shifts.id_employee', '=', 'employees.id')
			->leftjoin('users', 'employees.id_user', '=', 'users.id')
			->leftjoin('worksites', 'shifts.id_worksite', '=', 'worksites.id')
			->select(
				'shifts.*',
				'users.name as user_name',
				'users.surname as user_surname',
				'worksites.cod as worksite_cod'
			)
            ->addSelect(DB::raw("CONCAT(users.name, ' ', users.surname) as user_name_surname"))
			->where('worksites.id_responsable', '=', Auth::user()->employee->id)
			->where('shifts.date', '=', Carbon::now()->format('Y-m-d'))
			->where('validated', 0)
			->orderby('worksites.cod')
			->orderby('shifts.date', 'asc')
			->orderBy('shifts.start', 'asc')
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
			->addColumn('user_surname')
			->addColumn('user_name')
			->addColumn('user_name_surname')
			->addColumn('worksite_cod')
			->addColumn('date_formatted', fn (Shift $model) => $model->date->format('d/m/Y'))
			->addColumn('start', fn (Shift $model) => Carbon::parse($model->start)->format('H:i'))
			->addColumn('end', fn (Shift $model) => Carbon::parse($model->end)->format('H:i'))
			->addColumn('is_extraordinary')
		;
	}

	public function columns(): array
	{
		$canEdit = Auth::user()->role === UserRoleEnum::RESPONSIBLE->value;
		return [
			Column::action(__('general.action')),

			Column::make('Cognome', 'user_surname', 'users.surname')
                ->hidden()
                ->searchable(),
            
            Column::make('Nome', 'user_name', 'users.name')
                ->hidden()
                ->searchable(),

			Column::make(__('employee.user'), 'user_name_surname')
				->sortable()
				->searchable(),
			
			Column::make(__('worksite.cod'), 'worksite_cod', 'worksites.cod')
				->searchable(),

			Column::make(__('shift.date'), 'date_formatted', 'shifts.date'),

			Column::make(__('shift.start_time'), 'start'),

			Column::make(__('shift.end_time'), 'end'),

			Column::make(__('shift.is_extraordinary'), 'is_extraordinary')
				->toggleable($canEdit, 1, 0),
			];
	}

	public function onUpdatedToggleable(string $id, string $field, string $value): void
	{
		Shift::query()->find($id)->update([
			$field => $value,
		]);
	}

	public function filters(): array
	{
		return [
			Filter::datepicker('date'),
		];
	}


	public function actions(\App\Models\Shift $row): array
	{
		return [
			Button::add('present')
				->slot(Str::ucfirst(__('shift.present')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-green-600 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-green-700')
				->openModal('shift.present-modal', [
					'shift'	=> $row->id,
			]),

			Button::add('absent')
				->slot(Str::ucfirst(__('shift.absent')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-red-600 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-red-700')
				->openModal('shift.absent-modal', [
					'shift'	=> $row->id,
			]),

			Button::add('show-note')
				->slot(Str::ucfirst(__('shift.show_note')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-grey-600 dark:ring-offset-pg-primary-800 dark:text-black dark:bg-grey-700')
				->openModal('show-content-modal', [
					'title'	=> __('shift.notes'),
					'content'	=> $row->note,
			]),
		];
	}

	public function actionRules($row): array
	{
	   return [
			Rule::button('present')
				->when(fn () => $row->validated === 0)
				->hide(),
			
			Rule::button('absent')
				->when(fn () => $row->validated === 0)
				->hide(),
			
			Rule::button('show-note')
				->when(fn () => $row->note === null)
				->hide(),
		];
	}
}
