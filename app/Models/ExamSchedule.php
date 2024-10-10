<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSchedule extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $date = ['deleted_at'];



    protected $fillable = [
        // 'class_level',
        'exam_id',
        'uuid',
        'start_from',
        'end_on',
        'marking_from',
        'marking_to',
        'status',
        'created_by',
        'academic_year_id',
        'semester_id',
        'grading',
        // 'show_division',
        // 'avg_equation'

    ];
}
