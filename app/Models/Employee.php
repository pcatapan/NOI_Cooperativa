<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'number_serial',
        'iban',
        'work_hour_week_by_contract',
        'permission_hour_by_contract',
        'fiscal_code',
        'inps_number',
        'address',
        'city',
        'province',
        'cap',
        'phone',
        'notes',
        'date_of_hiring',
        'date_of_resignation',
        'job',
        'active'
    ];

    protected $cast = [
        'active' => 'boolean'
    ];

    protected $dates = [
        'date_of_hiring',
        'date_of_resignation'
    ];

    /*
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function worksites()
    {
        return $this->belongsToMany(Worksite::class, 'employee_worksites', 'id_employee', 'id_worksite');
    }

    public function worksitesAsResponsible()
    {
        return $this->hasMany(Worksite::class, 'id_responsable');
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'id_employee');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_employee');
    }

    /**
     * Override delete method
     */
    public function delete()
    {
        parent::delete();
        return $this->user->delete();
    }


    /*
     * Accessors
     */

    public function getFullNameAttribute()
    {
        return $this->user->name . ' ' . $this->user->surname;
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->city . ' (' . $this->province . ') ' . $this->cap;
    }
}
