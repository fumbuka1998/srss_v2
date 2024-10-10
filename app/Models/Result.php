<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $date = ['deleted_at'];

    protected $fillable = [
        'student_id',
        'exam_id',
        'score', 
        'full_name',
        'semester_id',
        'academic_year_id',
        'subject_id',
        'class_id',
        'stream_id',
        'uuid',
        'status',
        'created_by',
        'grade_group_id'
    ];



    public function exams(){

        return $this->belongsTo(Exam::class,'exam_id');


    }

    public function subjects(){

        return $this->belongsTo(Subject::class,'subject_id');
    }
}
