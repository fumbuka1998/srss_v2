<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'stream_id',
        'class_id',
        'academic_year_id',
        'uuid',
        'created_by'
    ];

    //created_by not added in migrations

}
