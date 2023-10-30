<?php

namespace App\Livewire\User;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
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
use PowerComponents\LivewirePowerGrid\Responsive;
use Illuminate\Support\Str;

final class UserTable extends PowerGridComponent
{
    use WithExport;
    public bool $multiSort = true;

    #[On('create')]
    public function create(): void
    {
        redirect()->route('user.add_edit');
    }

    public function setUp(): array
    {
        if (Auth::user()->role !== UserRoleEnum::ADMIN->value) {
			abort(403, __('general.403'));
		}

        //$this->showCheckBox();
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
                ->showRecordCount()
        ];
    }

    public function header(): array
    {
        return [
            Button::add('create')
                ->slot(__('user.create'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('create', []),

        ];
    }

    public function datasource(): Builder
    {
        return User::where('id', '!=', Auth::user()->id)
            ->where('role', UserRoleEnum::ADMIN->value)
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
            ->addColumn('surname')
            ->addColumn('email')
            ->addColumn('role', function (User $user) {
                return \App\Enums\UserRoleEnum::from($user->role)->labels();
            });
    }

    public function columns(): array
    {
        return [
            Column::action(__('general.action')),

            Column::make(Str::ucfirst(__('user.name')), 'name')
                ->sortable()
                ->searchable(),

            Column::make(Str::ucfirst(__('user.surname')), 'surname')
                ->sortable()
                ->searchable(),

            Column::make(Str::ucfirst(__('user.email')), 'email')
                ->sortable()
                ->searchable(),

            Column::make(Str::ucfirst(__('user.role')), 'role'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('role')
                ->dataSource([
                    ['label' => UserRoleEnum::from(UserRoleEnum::ADMIN->value)->labels(), 'value' => UserRoleEnum::ADMIN->value],
                    ['label' => UserRoleEnum::from(UserRoleEnum::RESPONSIBLE->value)->labels(), 'value' => UserRoleEnum::RESPONSIBLE->value],
                    ['label' => UserRoleEnum::from(UserRoleEnum::EMPLOYEE->value)->labels(), 'value' => UserRoleEnum::EMPLOYEE->value],
                ])
                ->optionValue('value')
                ->optionLabel('label'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
	public function edit($user): void
	{
        redirect()->route('user.add_edit', ['user' => $user]);
	}
    public function actions(\App\Models\User $row): array
    {
        return [
            Button::add('delete')
				->slot(Str::ucfirst(__('general.delete')))
				->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-red-600 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-red-700')
				->openModal('delete-modal', [
					'confirmationTitle'       => __('general.delete_confirmation_title'),
                    'confirmationDescription' => __('general.delete_confirmation_description'),
                    'id'                  => $row->id,
					'ids'					=> [],
					'class'					=> User::class,
                ]),

            Button::add('edit')
                ->slot(Str::ucfirst(__('general.edit')))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['user' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
       return [
        Rule::button('delete')
			->when(fn () => Auth::user()->role !== UserRoleEnum::ADMIN->value)
			->hide(),

        Rule::button('edit')
            ->when(fn () => $row->role !== UserRoleEnum::ADMIN->value)
            ->hide(),
        ];
    }
}
