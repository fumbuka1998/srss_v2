<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $date = ['deleted_at'];

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'total_marks',
        'passmark',
        'isCommutative',
        'grade_group',
        'created_by',
        'is_dp'
    ];
}
