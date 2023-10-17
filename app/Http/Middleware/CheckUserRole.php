<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::user() || Auth::user()->role !== $role) {
            return redirect('/'); // reindirizza dove vuoi se il ruolo non corrisponde
        }

        return $next($request);
    }
}
