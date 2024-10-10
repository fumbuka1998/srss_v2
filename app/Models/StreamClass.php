<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamClass extends Model
{
    use HasFactory;


    protected $fillable = [
        'stream_id',
        'class_id'

    ];
}
