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
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;

final class ShiftTable extends PowerGridComponent
{
	use WithExport;

	public bool $multiSort = true;
	public $worksite;

	public function setUp(): array
	{
		if (Auth::user()->role === UserRoleEnum::EMPLOYEE->value) {
			abort(403, __('general.403'));
		}

		$this->persist(['columns', 'filters']);

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
                ->slot(Str::ucfirst(__('employee.employee_create')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('shift.add-modal', [$this->worksite->id]),
        ];
    }

	public function datasource(): Builder
	{
		return Shift::query()
			->leftjoin('employees', function($employee) {
				$employee->on('shifts.id_employee', 'employees.id');
			})
			->leftjoin('users', function($user) {
				$user->on('employees.id_user', 'users.id');
			})
			->leftjoin('worksites', function($worksite) {
				$worksite->on('shifts.id_worksite', 'worksites.id');
			})
			->select(
				'shifts.*',
				'users.name as user_name',
				'users.surname as user_surname',
				'worksites.cod as worksite_cod'
			)
			->where('worksites.id', $this->worksite->id)
			->where('validated', 0)
			->orderBy('date', 'desc');
		;
	}

	public function relationSearch(): array
	{
		return [
			'employee' => [
				'users.name',
				'users.surname'
			]
		];
	}

	public function addColumns(): PowerGridColumns
	{
		return PowerGrid::columns()
			->addColumn('user_name')
			->addColumn('user_surname')
			->addColumn('date_formatted', fn (Shift $model) => Carbon::parse($model->date)->format('d/m/Y'))
			->addColumn('start', fn (Shift $model) => Carbon::parse($model->start)->format('H:i'))
			->addColumn('end', fn (Shift $model) => Carbon::parse($model->end)->format('H:i'))
			->addColumn('is_extraordinary')
		;
	}

	public function columns(): array
	{
		$canEdit = Auth::user()->role === UserRoleEnum::RESPONSIBLE->value;
		return [
			Column::action('Action'),

			Column::make(__('shift.is_extraordinary'), 'is_extraordinary')
				->toggleable($canEdit, 1, 0),

			Column::make(__('employee.name'), 'user_name', 'users.name')
				->sortable()
				->searchable(),

			Column::make(__('shift.date'), 'date_formatted', 'date')
				->sortable(),

			Column::make(__('shift.start_time'), 'start')
				->sortable()
				->searchable(),

			Column::make(__('shift.end_time'), 'end')
				->sortable()
				->searchable(),
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
			Filter::boolean('is_extraordinary')->label(__('general.yes'), __('general.no')),

			Filter::datepicker('date'),
		];
	}

	public function actions(\App\Models\Shift $row): array
	{
		return [
			Button::add('edit')
				->slot(Str::ucfirst(__('general.edit')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->openModal('shift.edit-modal', [
					'shift'	=> $row->id,
			]),
			
			Button::add('show-note')
				->slot(Str::ucfirst(__('shift.show_note')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-grey-600 dark:ring-offset-pg-primary-800 dark:text-black dark:bg-grey-700')
				->openModal('show-content-modal', [
					'title'	=> __('shift.notes'),
					'content'	=> $row->note,
			]),

			Button::add('delete')
				->slot(Str::ucfirst(__('general.delete')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-red-600 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-red-700')
				->openModal('delete-modal', [
					'confirmationTitle'       => __('general.delete_confirmation_title'),
					'confirmationDescription' => __('general.delete_confirmation_description'),
					'id'                  => $row->id,
					'ids'					=> [],
					'class'					=> Shift::class,
			]),
		];
	}

	public function actionRules($row): array
	{
	   return [
			Rule::button('show-note')
				->when(fn () => $row->note === null)
				->hide()
			,

			Rule::button('delete')
				->when(fn () => Auth::user()->role === UserRoleEnum::EMPLOYEE->value)
				->hide()
			,
		];
	}
}
