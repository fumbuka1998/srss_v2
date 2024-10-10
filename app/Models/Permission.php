<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'uuid',
        'created_by',
        'description'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
