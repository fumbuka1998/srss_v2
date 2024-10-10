<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;


    protected $fillable = ['name','uuid','parent_id'];


    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }


    public function getOutermostParent()
    {
        if ($this->parent) {
            return $this->parent->getOutermostParent();
        }
        return $this;
    }

    public function getDescendantCount()
    {
        return $this->countDescendants($this);
    }

    public function countDescendants($module)
    {
        $count = 0;

        foreach ($module->children as $child) {
            $count++; // Increment count for the current child
            $count += $this->countDescendants($child); // Recursively count descendants
        }

        return $count;
    }

    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }

    public function ModulePermissions(){
        return $this->hasMany(ModuleHasPermission::class);
    }

}
