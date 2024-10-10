<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultDraft extends Model
{
    use HasFactory;

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
        'uuid'
    ];



    public function exams(){

        return $this->belongsTo(Exam::class,'exam_id');


    }

    public function subjects(){

        return $this->belongsTo(Subject::class,'subject_id');
    }
}
