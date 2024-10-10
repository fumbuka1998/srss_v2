<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $date = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'username',
        'email',
        'password',
        'firstname',
        'lastname',
        'phone',
        'address',
        'profile_pic'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function setPasswordAttribute($password)
    {
        if (! empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function isAssignedToSubject(Subject $subject)
    {

    return $this->subjects->contains($subject);

    }

    public function getIsClassTeacher(){

       $checkpoint =  ClassTeacher::where('teacher_id',$this->id)->get()->count();
       return $checkpoint;

    }

    // public function classes()
    // {
    //     return $this->belongsToMany(ClassModel::class, 'student_class_pivot_table', 'student_id', 'class_id');
    // }


    public function assignedClasses(){
        $data = array();
        $assigned_classes = ClassTeacher::where('teacher_id',$this->id)->get();
        foreach ($assigned_classes as $key => $assigned) {
          $data[$key]['class_id'] = $assigned->class_id;
          $data[$key]['stream_id'] = $assigned->stream_id;
        }
        return $data;
  
      }

      public function assignedSubjects(){

        $data = array();
        $assigned_subjects = SubjectTeacher::where('teacher_id',$this->id)->get();
        if($assigned_subjects->count()){
            $data = $assigned_subjects;
        }

        return $data;


      }

    public function checkRole($role){

     $role_id = Role::where('name',$role)->first()->id;
     $checkpoint = UserHasRoles::where(['user_id'=>$this->id, 'role_id' => $role_id])->get()->count();
     return $checkpoint;


    }


    public function getNameAbbrvAttribute(){

        $str = $this->firstname.' '.$this->lastname;
        $words = explode(" ", $str);
        $initials = array_map(function ($word) {
        return strtoupper($word[0]);
        }, $words);

    $acronym = implode("", $initials);

    return strtoupper($acronym);


        }


        




    public function getFullNameAttribute(){

        return $this->firstname . '  '. $this->lastname;

    }


    public function userHasRoles(){

        return $this->hasMany(UserHasRoles::class);

    }

    public function permissions(){

        return $this->hasMany(RoleHasPermission::class,'role_id');

    }


    public function roleHasPermissions($module, $permission_id){


      $allowed = false;
      $roles =  $this->userHasRoles;

    foreach ($roles as $key => $role) {

     $permissions = RoleHasPermission::where('role_id', $role->role_id)->get();

     foreach ($permissions as $key => $permission) {

        if($permission->module_id == $module && $permission->permission_id == $permission_id) {
            $allowed = true;
            break;
        }

     }

      }

      return $allowed;

    }

    public function moduleParent($module_id){

        $roles =  $this->userHasRoles;
        $allowed = false;


        foreach ($roles as $key => $role) {

            $permissions = RoleHasPermission::where('role_id', $role->role_id)->get();

            foreach ($permissions as $key => $permission) {

                $parent_id = Module::find($permission->module_id)->getOutermostParent()->id;

                if($parent_id == $module_id) {

                    $allowed = true;
                    break;

            }
        }


    }

    return $allowed;

}








}
