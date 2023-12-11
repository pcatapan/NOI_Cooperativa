<?php

namespace App\Livewire\Holiday;

use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\Support\Str;

final class HolidayTable extends PowerGridComponent
{
    use WithExport;

    protected $listeners = ['updateSerach' => 'updateSerach'];
    public $worksite = null;

    public function setUp(): array
    {
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
                ->slot(Str::ucfirst(__('holiday.add_holiday')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->openModal('holiday.add-modal', []),
        ];
    }

    public function updateSerach($filters)
    {
        $this->worksite = $filters['worksite'];
    }

    public function datasource(): Builder
    {
        return Holiday::query()
            ->select('holidays.*')
            ->leftjoin('worksite_holiday', 'holidays.id', '=', 'worksite_holiday.holiday_id')
            ->when($this->worksite, function ($query) {
                $query->where('worksite_holiday.worksite_id', $this->worksite);
            })
            ->orderBy('date', 'asc')
            ->distinct()
        ;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name')
            ->addColumn('date')
            ->addColumn('date_formatted', function (Holiday $holiday) {
                return Carbon::parse($holiday->date)->format('d/m');
            })
            ->addColumn('is_recurring')
            ->addColumn('is_national')
        ;
    }

    public function columns(): array
    {
        return [
            Column::make(__('holiday.name'), 'name'),

            Column::make(__('holiday.date'), 'date_formatted', 'date'),

            Column::make(__('holiday.is_recurring'), 'is_recurring')
                ->toggleable(),

            Column::make(__('holiday.is_national'), 'is_national')
                ->toggleable(),

            Column::action(__('general.action'))
        ];
    }

    public function onUpdatedToggleable(string $id, string $field, string $value): void
	{
		Holiday::query()->find($id)->update([
			$field => $value,
		]);
	}

    public function filters(): array
    {
        return [
            //
        ];
    }

    public function actions(\App\Models\Holiday $row): array
    {
        return [
            Button::add('edit')
				->slot(Str::ucfirst(__('general.edit')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->openModal('holiday.edit-modal', [
					'holiday'	=> $row->id,
			]),
        ];
    }
}
