<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Level extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }
    public function subjects(){
        return $this->hasMany(Subject::class);
    }

}
