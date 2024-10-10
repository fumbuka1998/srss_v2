<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'uuid',
        'name',
        'education_level_id',
        'from',
        'to',
        'remarks',
        'points',
        'created_by'
    ];


    public function eLevels(){

        return $this->belongsTo(EducationLevel::class,'education_level_id');

    }
}
