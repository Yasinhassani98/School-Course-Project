<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory, Notifiable;
    protected $guarded = [];
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function parent()
    {
        return $this->belongsTo(Parint::class,'parint_id','id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function getImageURL()
    {
        if ($this->image) {

            return url("/storage/" . $this->image);
        } else {
            if ($this->gender == 'male') {

                return url("/images/user.png");
            } else {
                return url("/images/female.png");
            }
        }
    }
}
