<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_employee',
        'id_worksite',
        'date',
        'start',
        'end',
        'is_extraordinary',
        'validated',
        'note',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'is_extraordinary' => 'boolean',
        'validated' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    public function worksite()
    {
        return $this->belongsTo(Worksite::class, 'id_worksite');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_shift');
    }
}
