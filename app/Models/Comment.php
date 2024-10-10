<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['student_result_report_id','predefined_comment_id','student_id','user_id','type'];



    public function itsHmReport()
    {
        return $this->hasOne(StudentResultReport::class, 'hm_comment', 'id');
    }

    // Define a one-to-one relationship with StudentResultReport for its_ct
    public function itsCtReport()
    {
        return $this->hasOne(StudentResultReport::class, 'ct_comment', 'id');
    }

    // Define a one-to-one relationship with PredefinedComment for its_hm
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
