<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamScheduleClassStream extends Model
{
    use HasFactory;

    protected $fillable = ['class_id','stream_id','exam_schedule_id'];
    
}
