<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id','subject_id'];


    public function subjects(){

        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function exams(){

        return $this->belongsTo(Exam::class, 'exam_id');

    }


}
