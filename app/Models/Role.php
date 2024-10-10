<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;


class Role extends Model
{
    use HasFactory;
    use HasPermissions;


    protected $fillable = [
        'name',
        'guard_name',
        'uuid',
        'description',
        'type'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles');
    }

    

}
