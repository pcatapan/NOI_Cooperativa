<?php

namespace App\Livewire\Company;

use App\Models\Company;
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
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;

final class CompanyTable extends PowerGridComponent
{
    use WithExport;

	public bool $multiSort = true;

    #[On('create')]
    public function create(): void
    {
        redirect()->route('company.add_edit');
    }
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
                ->slot(Str::ucfirst(__('company.create')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('create', []),

        ];
    }

    public function datasource(): Builder
    {
        return Company::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name')
            ->addColumn('vat_number')
            ->addColumn('address', fn (Company $company) => $company->address ?? '-')
            ->addColumn('city', fn (Company $company) => $company->city ?? '-')
            ->addColumn('province', fn (Company $company) => $company->province ?? '-')
            ->addColumn('zip_code', fn (Company $company) => $company->zip_code ?? '-')
            ->addColumn('phone', fn (Company $company) => $company->phone ? '<a href="tel:'.$company->phone.'">'.$company->phone.'</a>' : '-')
            ->addColumn('pec', fn (Company $company) => $company->pec ? '<a href="mailto:'.$company->pec.'">'.$company->pec.'</a>' : '-');
        ;
    }

    public function columns(): array
    {
        return [
            Column::action(__('general.action')),

            Column::make(__('company.name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('company.vat_number'), 'vat_number')
                ->sortable()
                ->searchable(),

            Column::make(__('company.address'), 'address')
                ->sortable(),

            Column::make(__('company.city'), 'city')
                ->sortable(),

            Column::make(__('company.province'), 'province')
                ->sortable(),

            Column::make(__('company.zip_code'), 'zip_code')
                ->sortable(),

            Column::make(__('company.phone'), 'phone')
                ->sortable(),

            Column::make(__('company.pec'), 'pec')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            //
        ];
    }

    #[\Livewire\Attributes\On('edit')]
	public function edit($company): void
	{
        redirect()->route('company.add_edit', ['company' => $company]);
	}
    public function actions(\App\Models\Company $row): array
    {
        return [
            Button::add('edit')
				->slot(Str::ucfirst(__('general.edit')))
				->id()
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
				->dispatch('edit', ['company' => $row->id]),

            Button::add('delete')
				->slot(Str::ucfirst(__('general.delete')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-red-600 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-red-700')
				->openModal('delete-modal', [
					'confirmationTitle'       => __('general.delete_confirmation_title'),
                    'confirmationDescription' => __('general.delete_confirmation_description'),
                    'id'                  => $row->id,
					'ids'					=> [],
					'class'					=> Company::class,
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
