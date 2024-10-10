<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationError extends Model
{
    use HasFactory;

    protected $fillable = ['payload','user_id'];
    
}
