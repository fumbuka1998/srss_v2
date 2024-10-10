<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StudentPromotion extends Model
{
    use HasFactory;


    protected $fillable = [
        'student_id', 'student_name', 'gender', 'from_class', 'from_stream', 'to_class', 'to_stream'
    ];

    
}
