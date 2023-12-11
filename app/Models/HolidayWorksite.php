<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayWorksite extends Model
{
    use HasFactory;

    protected $table = 'worksite_holiday';
    
    protected $fillable = [
        'worksite_id',
        'holiday_id',
    ];

    public function worksite()
    {
        return $this->belongsTo(Worksite::class);
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class);
    }
}
