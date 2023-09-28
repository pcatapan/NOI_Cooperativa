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
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

use PowerComponents\LivewirePowerGrid\Responsive;

final class UserTable extends PowerGridComponent
{
    use WithExport;
    public bool $multiSort = true;

    #[On('create')]
    public function create(): void
    {
        redirect()->route('employee.create');
    }

    public function setUp(): array
    {
        //$this->showCheckBox();
        $this->persist(['columns', 'filters']);


        return [
            Responsive::make(),

            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            
                Header::make()->showSearchInput(),
            
            Footer::make()
                ->showPerPage()
                ->showRecordCount()
        ];
    }

    public function header(): array
    {
        return [
            Button::add('create')
                ->slot(__('employee.create'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('create', []),

        ];
    }

    public function datasource(): Builder
    {
        return User::where('id', '!=', Auth::user()->id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name', function (User $user) {
                return $user->name.' '.$user->surname;
            })
            ->addColumn('email')
            ->addColumn('role', function (User $user) {
                return \App\Enums\UserRoleEnum::from($user->role)->labels();
            })
            ->addColumn('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Role', 'role')
                ->searchable(),


            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::enumSelect('role', 'role')
                ->dataSource(UserRoleEnum::cases())
                ->optionLabel('users.role'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(\App\Models\User $row): array
    {
        return [
            //Button::add('edit')
            //    ->slot('Edit: '.$row->id)
            //    ->id()
            //    ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
            //    ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
