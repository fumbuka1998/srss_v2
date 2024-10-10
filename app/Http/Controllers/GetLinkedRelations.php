<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectEducationLevel;
use Illuminate\Http\Request;

class GetLinkedRelations extends Controller
{



    public function streams( Request $req){


     $streams =  Stream::where('class_id',$req->id)->get();
     $elevelid = SchoolClass::find($req->id)->education_level_id;

     $students = SchoolClass::find($req->id)->students;
    //  return $elevelid;
    $subject_links = SchoolClass::with('educationLevels.subjectEducationLevels.subjects')->where('education_level_id',$elevelid)->get();

    $subjects = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
                        ->join('education_levels','education_levels.id','=','subject_education_levels.education_level_id')
                        ->select('subjects.name as sbjct_name','subjects.id as sbjct_id')
                        ->where('education_level_id',$elevelid)
                        ->get();

     $subjects_html = '<option> </option>';
     $students_html = '<option> </option>';
     foreach ($subjects as $key => $subject) {
        $subjects_html .= '<option value="'.$subject->sbjct_id.'"> '.$subject->sbjct_name.' </option>';
     }

     $streams_html = '<option> </option>';

     foreach ($streams as $key => $stream) {

        $streams_html .= '<option value="'.$stream->id.'"> '.$stream->name.' </option>';

     }

     foreach ($students as $key => $student) {

        $students_html .= '<option value="'.$student->id.'"> '.$student->full_name.' <option/>';


     }

     $data['streams'] = $streams_html;
     $data['subjects'] = $subjects_html;
     $data['students'] = $students_html;

     return response($data);

    }



    public function exams(){



    }

    public function subjects(Request $req){






    }


    public function terms(Request $req){
      // return $req;
      $terms = AcademicYear::find($req->id)->terms;
      $html = '<option> </option>';
      foreach ($terms as $key => $term) {
        $html.= '<option value="'.$term->id.'"> '.$term->name.' </option>';

      }

      return response($html);

    }



}
