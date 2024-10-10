<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeGroup extends Model
{
    use HasFactory;


    protected $fillable = ['name','uuid'];


    public function grades(){
        return $this->hasMany(Grade::class,'group_id');
    }
}
