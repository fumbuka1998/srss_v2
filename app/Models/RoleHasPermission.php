<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;

    protected $fillable = [

        'role_id',
        'permission_id',
        'module_id'

    ];

    public function roles(){
        return $this->belongsTo(Role::class,'role_id');
    }

    public function permissions(){

        return $this->belongsTo(Permission::class,'permission_id');

    }


    // public $timestamps = false;


}
