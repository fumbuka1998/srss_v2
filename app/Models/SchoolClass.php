<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    // use SoftDeletes;
    use HasFactory;

    // protected $date = ['deleted_at'];


    protected $fillable = [
        'uuid',
        'name',
        'education_level_id',
        'capacity',
        'created_by'
    ];


    public function educationLevels(){

        return $this->belongsTo(EducationLevel::class, 'education_level_id');

    }

    public function streams(){
        return $this->hasMany(Stream::class,'class_id');
    }

    public function students(){
        return $this->hasMany(Student::class,'class_id');
    }


}
