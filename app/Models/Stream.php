<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stream extends Model
{
    use Softdeletes;
    use HasFactory;

    protected $date = ['deleted_at'];

    protected $fillable = [
        'uuid',
        'name',
        'class_id',
        'capacity',
        'code',
        'created_by'
    ];

    public function classes(){
        return $this->belongsTo(SchoolClass::class,'class_id');
    }
}
