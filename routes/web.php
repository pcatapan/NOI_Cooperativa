<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRoleEnum;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', fn () => redirect()->route('login'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    /**
     * Usere
     */
    Route::get('users', fn () => view('livewire.user.index'))->name('users.index');
    Route::post('/user', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user?}', 'App\Livewire\User\AddEdit')->name('user.add_edit');

    /**
     * Employee
     */
    Route::get('employees', fn () => view('livewire.employee.index'))->name('employees.index');
    Route::get('/employee/{employee?}', 'App\Livewire\Employee\AddEdit')->name('employee.add_edit');

    /**
     * Company
     */
    Route::get('companies',fn () => view('livewire.company.index'))->name('companies.index');
    Route::get('/company/{company?}', 'App\Livewire\Company\AddEdit')->name('company.add_edit');

    /**
     * Worksite
     */
    Route::get('worksites', fn () => view('livewire.worksite.index'))->name('worksites.index');
    Route::get('/worksite/{worksite?}', 'App\Livewire\Worksite\AddEdit')->name('worksite.add_edit');

    /**
     * Report
     */
    Route::get('reports/employee', fn () => view('livewire.report.employee'))->name('reports.employee');
    Route::get('reports/company', fn () => view('livewire.report.company'))->name('reports.company');
    Route::get('reports/worksite', fn () => view('livewire.report.worksite'))->name('reports.worksite');

    /**
     * Shift
     */
    //Route::get('shifts', fn () => view('livewire.shift.index'))->name('shifts.index');
    Route::get('/shift-not-validated', fn () => view('livewire.shift.not-validated'))->name('shifts.not_validated');
    Route::get('/shift-future', fn () => view('livewire.shift.future'))->name('shifts.future');

    /**
     * Presence
     */
    Route::get('presences', fn () => view('livewire.presence.index'))->name('presences.index');
    Route::get('absence', fn () => view('livewire.presence.absence'))->name('absence.index');


});

require __DIR__.'/auth.php';
