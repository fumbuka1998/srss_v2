<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subject extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $date = ['deleted_at'];

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'department_id',
        'subject_id',
        'subject_type',
        'points',
        'created_by'
    ];


    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }

    public function subjectEducationLevels(){
        return $this->hasMany(SubjectEducationLevel::class);
    }

    public function educationLevels(){
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }


    public function scopeAssignedToUser($query, User $user)
    {
        return $query->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

    }


    public function scopeAssignedToUserOrTeacher($query, User $user, $classId, $streamId, $subjectId)
    {
        return $query->where(function ($q) use ($user, $classId, $streamId, $subjectId) {
            $q->where('teacher_id', $user->id) // Check if the user is the teacher.
            ->orWhere(function ($q) use ($classId, $streamId, $subjectId) {
                $q->where('class_id', $classId)
                    ->where('stream_id', $streamId)
                    ->where('subject_id', $subjectId);
            });
        });
    }

}
