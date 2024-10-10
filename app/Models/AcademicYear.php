<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'from',
        'to',
        'status',
        'created_by'
    ];


    public function terms(){
        return $this->hasMany(Semester::class);
    }
}
