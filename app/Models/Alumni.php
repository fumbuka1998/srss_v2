<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;


    // protected $fillable = [
    //     'name', 'student_id', 'academic_year', 'achievement'
    // ];

    protected $fillable = [
        'name', 'student_id','admission_no','class_id','stream_id','graduation_year', 'academic_year', 'achievement'
    ];


    public function getClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }
}
