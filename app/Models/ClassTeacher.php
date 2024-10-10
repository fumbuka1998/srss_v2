<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassTeacher extends Model
{
    // use SoftDeletes;
    use HasFactory;

    // protected $date = ['deleted_at'];

    protected $fillable = [
        'teacher_id',
        'class_id',
        'stream_id',
        'uuid',
        'level_flag',
        'academic_year_id',
        'created_by'
    ];

}
