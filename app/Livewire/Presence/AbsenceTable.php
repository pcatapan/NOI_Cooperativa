<?php

namespace App\Livewire\Presence;

use App\Enums\PresesenceTypeEnum;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRoleEnum;
use App\Models\Shift;
use Illuminate\Support\Str;

final class AbsenceTable extends PowerGridComponent
{
	use WithExport;

	// TODO : questa va cambiato con la tab presenze

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

			Header::make(),

			Footer::make()
				->showPerPage()
				->showRecordCount(),
		];
	}

	public function header(): array
    {
        return [
            Button::add('create')
                ->slot(Str::ucfirst(__('shift.create')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('shift.add-modal', [$this->worksite->id]),
        ];
    }

	public function datasource(): Builder
	{
		return Presence::query()
            ->leftjoin('shifts', function($shift) {
                $shift->on('presences.id_shift', 'shifts.id');
            })
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
				'presences.*',
				'users.name as user_name',
				'users.surname as user_surname',
                'shifts.is_extraordinary as is_extraordinary',
                'shifts.id as shift_id',
                'shifts.note as note',
				'worksites.cod as worksite_cod',
			)
            ->addSelect(DB::raw("CONCAT(users.name, ' ', users.surname) as user_name_surname"))
			->where('worksites.id', $this->worksite->id)
			->where('shifts.validated', 1)
            ->where('presences.absent', 1)
			->orderBy('date', 'desc');
		;
	}

	public function relationSearch(): array
	{
		return [
            'shift' => [
                'employee' => [
                    'users.name',
                    'users.surname'
                ]
            ]
		];
	}

	public function addColumns(): PowerGridColumns
	{
		return PowerGrid::columns()
			->addColumn('id')
            ->addColumn('shift_id')
			->addColumn('user_surname')
            ->addColumn('user_name')
            ->addColumn('user_name_surname')
			->addColumn('date_formatted', fn (Presence $model) => Carbon::parse($model->date)->format('d/m/Y'))
			->addColumn('start', fn (Presence $model) => Carbon::parse($model->time_entry)->format('H:i'))
			->addColumn('end', fn (Presence $model) => Carbon::parse($model->time_exit)->format('H:i'))
			->addColumn('is_extraordinary')
		;
	}

	public function columns(): array
	{
		$canEdit = Auth::user()->role === UserRoleEnum::RESPONSIBLE->value;
		return [
			Column::action(__('general.action')),

			Column::make(__('general.id'), 'id')
				->hidden(),
            
            Column::make(__('general.id'), 'shift_id')
                ->hidden(),

			Column::make(__('shift.is_extraordinary'), 'is_extraordinary')
				->toggleable($canEdit, 1, 0),

			Column::make('Cognome', 'user_surname', 'users.surname')
                ->hidden()
                ->searchable(),
            
            Column::make('Nome', 'user_name', 'users.name')
                ->hidden()
                ->searchable(),

			Column::make(__('employee.user'), 'user_name_surname')
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

        Presence::query()->where('id_shift', $id)->update([
            'type' => $value ? PresesenceTypeEnum::EXTRAORDINARY->value : PresesenceTypeEnum::ORDINARY->value,
        ]);
	}

	public function filters(): array
	{
		return [
			Filter::boolean('is_extraordinary')->label(__('general.yes'), __('general.no')),

			Filter::datepicker('date'),
		];
	}

	public function actions(\App\Models\Presence $row): array
	{
		return [
			Button::add('edit')
				->slot(Str::ucfirst(__('general.edit')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->openModal('shift.edit-modal', [
					'shift'	=> $row->shift_id,
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
					'id'                    => $row->shift_id,
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
