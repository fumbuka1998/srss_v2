<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;


    protected $fillable = [
        'uuid',
        'name',
        'code',
        'created_by'
    ];


    public function classes(){
        return $this->hasMany(SchoolClass::class);
    }

    public function subjectEducationLevels(){
        return $this->hasMany(SubjectEducationLevel::class);
    }

    public function subjects(){
        return $this->hasMany(Subject::class);
    }
}
