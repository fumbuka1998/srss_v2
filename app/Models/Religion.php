<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name', 
        'created_by',
    ];

    public function sects(){

        return $this->hasMany(ReligionSect::class,'religion_id');

    } 
}
