<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'academic_year_id',
        'from',
        'to',
        'duration',
        'status',
        'created_by'
    ];

    public function academicYear(){
        return $this->belongsTo(AcademicYear::class,'academic_year_id');
    }
}
