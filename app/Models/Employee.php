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
