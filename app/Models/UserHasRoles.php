<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasRoles extends Model
{
    // use SoftDeletes;
    use HasFactory;

    // protected $date = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'role_id'
    ];


    public function roles(){
        return $this->belongsTo(Role::class,'role_id');

    }

    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }

}
