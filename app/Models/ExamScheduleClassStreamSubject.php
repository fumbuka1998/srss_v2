<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamScheduleClassStreamSubject extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $date = ['deleted_at'];

    protected $fillable = ['class_id','stream_id','exam_schedule_id','subject_id','status','created_by','uuid'];

}
