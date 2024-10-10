<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectsAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id','uuid','student_id','stream_id','class_id'];


    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id'); 
    }

}
