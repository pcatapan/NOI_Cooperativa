<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_employee',
        'id_worksite',
        'id_shift',
        'date',
        'time_entry',
        'time_exit',
        'time_entry_extraordinary',
        'time_exit_extraordinary',
        'minutes_worked',
        'minutes_extraordinary',
        'motivation_extraordinary',
        'absent',
        'type_absent',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
        'minutes_worked' => 'decimal:2',
        'minutes_extraordinary' => 'decimal:2',
        'absent' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    public function worksite()
    {
        return $this->belongsTo(Worksite::class, 'id_worksite');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }
}
