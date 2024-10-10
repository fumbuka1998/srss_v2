<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Student extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $fillable = [

        'uuid',
        'firstname',
        'middlename',
        'lastname',
        'profile_pic',
        'class_id',
        'stream_id',
        'isDisabled',
        'dob',
        'gender',
        'nationality',
        'religion_id',
        'house_id',
        'tribe',
        'religion_sect_id',
        'admission_type',
        'registration_date',
        'created_by',
        'admission_no',
        'isgraduated'

    ];

    //admission_no in


    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function religionSect()
    {
        return $this->belongsTo(ReligionSect::class, 'religion_sect_id');
    }


    public function subjectsAssignments() {
        return $this->hasMany(StudentSubjectsAssignment::class, 'student_id');
    }

    public function getFullNameAttribute(){

        return $this->firstname.' '.$this->middlename.' '.$this->lastname;

    }


    public function getClass(){
        return $this->belongsTo(SchoolClass::class,'class_id');
    }


    public function stream(){

        return $this->belongsTo(Stream::class, 'stream_id');

    }


    public function results(){

        return $this->hasMany(Result::class,'student_id');
    }

    public function assignments()
    {
        return $this->hasMany(StudentSubjectsAssignment::class, 'student_id');
    }

    public function getNameAbbrvAttribute(){

        // return 'aa';

        $str = $this->firstname.' '.$this->lastname;

        // return $str;
        $words = explode(" ", $str);

        $initials = array_map(function($word) {
            return substr($word, 0, 1);
        }, $words);

    $acronym = implode("", $initials);

    return strtoupper($acronym);


        }
}
