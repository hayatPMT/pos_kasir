<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }
}
