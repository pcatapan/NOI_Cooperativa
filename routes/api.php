<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Serach for select
 */
Route::get('/company', [App\Http\Controllers\CompanyController::class, '__index'])->name('api.companies');
Route::get('/responsable', [App\Http\Controllers\UserController::class, '__indexResponsible'])->name('api.responsible');
Route::get('/employee', [App\Http\Controllers\UserController::class, '__indexEmployee'])->name('api.employee');
Route::get('/employee/{responsable}', [App\Http\Controllers\UserController::class, '__indexEmployeeByResponsable'])->name('api.employee_by_responsable');
Route::get('/worksite', [App\Http\Controllers\WorksiteController::class, '__index'])->name('api.worksite');
Route::get('/worksite/{responsable}', [App\Http\Controllers\WorksiteController::class, '__indexByResponsable'])->name('api.worksite_by_responsable');

Route::middleware('auth')->group(function () {

});
