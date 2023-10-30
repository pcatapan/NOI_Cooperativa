<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        
        if ($role === UserRoleEnum::RESPONSIBLE->value) {
            return app()->call('App\Livewire\Dashboard\Responsible');
        }
        
        if ($role === UserRoleEnum::ADMIN->value) {
            return app()->call('App\Livewire\Dashboard\Admin');
        }

        if ($role === UserRoleEnum::EMPLOYEE->value) {
            return app()->call('App\Livewire\Dashboard\Employee');
        }

        abort(403, 'Accesso non autorizzato');
    }
}
