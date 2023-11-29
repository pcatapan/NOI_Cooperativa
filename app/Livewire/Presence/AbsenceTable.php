<?php

namespace App\Livewire\Presence;

use App\Enums\PresenceAbsentTypeEnum;
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
			->leftjoin('employees', function($employee) {
				$employee->on('presences.id_employee', 'employees.id');
			})
			->leftjoin('users', function($user) {
				$user->on('employees.id_user', 'users.id');
			})
			->leftjoin('worksites', function($worksite) {
				$worksite->on('presences.id_worksite', 'worksites.id');
			})
			->select(
				'presences.*',
				'users.name as user_name',
				'users.surname as user_surname',
				'worksites.cod as worksite_cod',
			)
            ->addSelect(DB::raw("CONCAT(users.name, ' ', users.surname) as user_name_surname"))
			->where('worksites.id', $this->worksite->id)
            ->where('presences.absent', 1)
			->orderBy('presences.date', 'desc');
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
			->addColumn('id')
            ->addColumn('id_shift')
			->addColumn('user_surname')
            ->addColumn('user_name')
            ->addColumn('user_name_surname')
			->addColumn('date_formatted', fn (Presence $model) => Carbon::parse($model->date)->format('d/m/Y'))
			->addColumn('start', fn (Presence $model) => Carbon::parse($model->time_entry)->format('H:i'))
			->addColumn('end', fn (Presence $model) => Carbon::parse($model->time_exit)->format('H:i'))
			->addColumn('type', fn (Presence $model) => $model->type === PresesenceTypeEnum::ORDINARY->value ? 0 : 1)
			->addColumn('type_absent', fn (Presence $model) => PresenceAbsentTypeEnum::from($model->type_absent)->labels())
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

			Column::make(__('shift.is_extraordinary'), 'type')
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

			Column::make(__('shift.motivation_absent'), 'type_absent'),
		];
	}

	public function onUpdatedToggleable(string $id, string $field, string $value): void
	{
        $presence = Presence::find($id);
		
		$presence->type = $value ? PresesenceTypeEnum::EXTRAORDINARY->value : PresesenceTypeEnum::ORDINARY->value;
		$presence->save();
	}

	public function filters(): array
	{
		return [
            Filter::inputText('user_name_surname')
				->operators(['contains'])
				->builder(function (Builder $query, $value) {
					// Verifica che $value sia un array e che la chiave 'value' sia impostata e non vuota
					if (is_array($value) && !empty($value['value'])) {
						return $query->whereRaw("CONCAT(users.name, ' ', users.surname) LIKE ?", ["%{$value['value']}%"]);
					}

					return $query;
				}),
                
			Filter::boolean('type')->label(__('general.yes'), __('general.no')),

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
					'shift'	=> $row->id_shift,
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
					'id'                    => $row->id_shift,
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
