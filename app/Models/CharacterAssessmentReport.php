<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterAssessmentReport extends Model
{
    use HasFactory;

    // protected $fillable = ['generated_exam_report_id','student_id','code','grade'];

    protected $fillable = ['generated_exam_report_id','student_id','code','grade','attendance','late'];

}
