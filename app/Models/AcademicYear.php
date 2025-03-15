<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'is_current',
    ];
    protected $casts = [
        'is_current' => 'boolean',
    ];
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
