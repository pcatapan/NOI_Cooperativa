<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', function () {
    return redirect()->route('login');
 })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    /**
     * Usere
     */
    Route::get('users', function () {
        return view('livewire.user.index');
    })->name('users.index');
    Route::post('/user', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::get('/user', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');

    /**
     * Employee
     */
    Route::get('/employee', [App\Http\Controllers\UserController::class, 'create'])->name('employee.create');


});

require __DIR__.'/auth.php';
