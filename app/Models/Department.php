<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'created_by',
        'hod_id'
    ];


    public function subjects(){
        return $this->hasMany(Subject::class);
    }
}
