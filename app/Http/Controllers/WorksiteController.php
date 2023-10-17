<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Worksite;

class WorksiteController extends Controller
{
    public function __index()
    {
        $companies = Worksite::all()->map(function ($worksite) {
            return [
                'id' => $worksite->id,
                'cod' => $worksite->cod,
            ];
        });

        return $companies;
    }

    public function __indexByResponsable(Employee $responsable)
    {
        $companies = Worksite::where('id_responsable', $responsable->id)->get()->map(function ($worksite) {
            return [
                'id' => $worksite->id,
                'cod' => $worksite->cod,
            ];
        });

        return $companies;
    }
}
