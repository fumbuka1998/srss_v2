<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleHasPermission extends Model
{
    use HasFactory;

    protected $fillable = ['module_id','permission_id','uuid'];

    public function permissions(){
        return $this->belongsTo(Permission::class,'permission_id');
    }

}
