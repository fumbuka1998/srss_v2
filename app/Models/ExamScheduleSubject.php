<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamScheduleSubject extends Model
{
    use HasFactory;


    protected $fillable = ['exam_schedule_id','subject_id'];
}
