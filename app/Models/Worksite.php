<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worksite extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'cod',
        'address',
        'city',
        'province',
        'zip_code',
        'id_responsable',
        'id_company',
        'total_hours',
        'total_hours_extraordinary',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function responsible()
    {
        return $this->belongsTo(Employee::class, 'id_responsable');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_worksites', 'id_worksite', 'id_employee');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_worksite');
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'id_worksite');
    }
}
