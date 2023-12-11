<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'is_recurring',
        'is_national',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_recurring' => 'boolean',
        'is_national' => 'boolean',
    ];

    public function worksites()
    {
        return $this->belongsToMany(Worksite::class, 'worksite_holiday', 'holiday_id', 'worksite_id');
    }
}
