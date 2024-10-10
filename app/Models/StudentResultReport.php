<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResultReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'generated_exam_report_id',
        'student_id',
        'full_name',
        'metadata',
        'class_position',
        'stream_position',
        'user_id',
        'division',
        'points',
        'grade',
        'avg',
        'remarks'
    ]; 

    //not added remarks in migration

    public function itsHmPredefinedComment()
    {
        return $this->belongsTo(PredefinedComment::class, 'hm_comment', 'id');
    }

    // Define a one-to-one relationship with PredefinedComment for its_ct
    public function itsCtPredefinedComment()
    {
        return $this->belongsTo(PredefinedComment::class, 'ct_comment', 'id');
    }

}
