<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamScheduleClass extends Model
{
    use HasFactory;
    protected $fillable = ['exam_schedule_id','class_id'];
}
