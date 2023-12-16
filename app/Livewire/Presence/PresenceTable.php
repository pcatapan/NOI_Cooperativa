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
use App\Http\Services\UtilsServices;
use App\Models\Shift;
use Illuminate\Support\Str;

final class PresenceTable extends PowerGridComponent
{
	use WithExport;

	public bool $multiSort = true;
	public $worksite;

	public function setUp(): array
	{
		if (Auth::user()->role === UserRoleEnum::EMPLOYEE->value) {
			abort(403, __('general.403'));
		}

		//$this->persist(['columns', 'filters']);
		$this->showCheckBox('id_shift');


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

			Button::add('duplicate')
				->slot(__('shift.duplicate')  . '(<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)')
				->class('inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150
                     bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-200 disabled:opacity-25
                     dark:bg-blue-700 dark:hover:bg-blue-800 dark:border-blue-800')
				->dispatch('duplicate', []),
        ];
    }

	protected function getListeners(): array
    {
        return array_merge(
            parent::getListeners(), [
                'duplicate',
            ]);
    }

    public function duplicate(): void
    {
        $this->dispatch('openModal', 'shift.duplicate', [
            'shifts'                 => $this->checkboxValues,
        ]);
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
            ->where('presences.absent', 0)
			->orderBy('presences.date', 'desc');
		;
	}

	public function relationSearch(): array
	{
		return [
			'employees' => [
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
		;
	}

	public function columns(): array
	{
		$canEdit = Auth::user()->role === UserRoleEnum::RESPONSIBLE->value;
		return [
			Column::action(__('general.action'))
                ->visibleInExport(false),

			Column::make(__('general.id'), 'id')
				->hidden(),
            
            Column::make(__('general.id'), 'id_shift')
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

			Button::make('alert_night', '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
				</svg>')
                ->class('items-center flex justify-center h-full')
                ->tooltip(__('shift.night_shift')),

			Button::make('alert_holiday', '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
		  		</svg>')
                ->class('items-center flex justify-center h-full')
                ->tooltip(__('shift.holidat_shift'))
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

			Rule::button('alert_night')
				->when(fn($row) => Carbon::parse($row->start)->format('H:i') <= '22:00' && Carbon::parse($row->end)->format('H:i') >= '06:00')
                ->hide()
			,

			Rule::button('alert_holiday')
				->when(fn($row) => UtilsServices::isHoliday($row->worksite, $row->date) == false)
                ->hide()
			,
		];
	}
}
