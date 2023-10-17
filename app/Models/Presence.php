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
        'hours_worked',
        'hours_extraordinary',
        'motivation_extraordinary',
        'absent',
        'type_absent',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_worked' => 'decimal:2',
        'hours_extraordinary' => 'decimal:2',
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
