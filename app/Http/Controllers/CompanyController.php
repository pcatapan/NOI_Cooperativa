<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CompanyController extends Controller
{
    public function __index(Request $request)
    {
        return Company::query()
            ->when(
                $request->search,
                fn (Builder $query) => $query->where('name', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                ];
            })
            ;
    }
}
