<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectEducationLevel extends Model
{
    use HasFactory;
    protected $fillable =['subject_id','education_level_id'];

    public function subjects(){
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function educationlevels(){

        return $this->belongsTo(EducationLevel::class,'education_level_id');

    }
}
