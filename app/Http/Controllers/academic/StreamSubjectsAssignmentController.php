<?php

namespace App\Http\Controllers\academic;

use App\Http\Controllers\Controller; 
use App\Models\AcademicYear;
use App\Models\ClastreamSubject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectEducationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StreamSubjectsAssignmentController extends Controller
{

    public function index_tq(){


       $data['class_streams'] = SchoolClass::leftjoin('streams','school_classes.id','=','streams.class_id')
                                ->select('streams.name as stream_name','school_classes.name as class_name','school_classes.id as class_id','streams.id as stream_id')->get();

        $data['subjects'] = Subject::all();

        $data['assignedSubjects'] = ClastreamSubject::join('subjects','clastream_subjects.subject_id','=','subjects.id')
                                                   ->join('school_classes','school_classes.id','=','clastream_subjects.class_id')
                                                   ->join('streams','streams.id','=','clastream_subjects.stream_id')
                                                   ->select('school_classes.name as class_name','clastream_subjects.stream_id','clastream_subjects.class_id','clastream_subjects.subject_id','subjects.name as subject_name','clastream_subjects.id as the_id','clastream_subjects.subject_id')
                                                   ->get();
        $csts = $data['class_streams'];
        $data['csts'] = json_encode($csts); 

        return view('configurations.academic.subjects_allocation.index')->with($data);

      }


      public function index_edit_sat()
      {
           $data['class_streams'] = DB::table('school_classes')
                ->select(
                    'school_classes.id as class_id',
                    'school_classes.name as class_name',
                    'education_levels.name as education_level_name',
                    'streams.name as stream_name',
                    'streams.id as stream_id' 
                )
                ->leftJoin('education_levels', 'school_classes.education_level_id', '=', 'education_levels.id')
                ->leftJoin('streams', 'school_classes.id', '=', 'streams.class_id')
                ->get();

       
          $data['subjects'] = DB::table('subjects')
                ->select('subjects.*', 'education_levels.name as education_level_name')
                ->leftJoin('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')
                ->leftJoin('education_levels', 'subject_education_levels.education_level_id', '=', 'education_levels.id')
                ->get();
                
          
          $data['assignedSubjects'] = ClastreamSubject::with('subject')
              ->select('class_id', 'stream_id', 'subject_id')
              ->groupBy(['class_id', 'stream_id', 'subject_id'])
              ->get();

          // Pass data to the view
          return $data['csts'] = $data['class_streams']->toJson();
          return view('configurations.academic.subjects_allocation.index')->with($data);
      }

      
      // new index view
      public function index()
      {
        //   $data['class_streams'] = DB::table('school_classes')
        //       ->select(
        //           'school_classes.id as class_id',
        //           'school_classes.name as class_name',
        //           'education_levels.name as education_level_name',
        //           'streams.name as stream_name',
        //           'streams.id as stream_id' 
        //       )
        //       ->leftJoin('education_levels', 'school_classes.education_level_id', '=', 'education_levels.id')
        //       ->leftJoin('streams', 'school_classes.id', '=', 'streams.class_id')
        //       ->get();

            $data['class_streams'] = DB::table('school_classes')
                ->select(
                    'school_classes.id as class_id',
                    'school_classes.name as class_name',
                    'education_levels.name as education_level_name',
                    'streams.name as stream_name',
                    'streams.id as stream_id' 
                )
                ->leftJoin('education_levels', 'school_classes.education_level_id', '=', 'education_levels.id')
                ->leftJoin('streams', function ($join) {
                    $join->on('school_classes.id', '=', 'streams.class_id')
                        ->whereNull('streams.deleted_at');
                })
                ->get();

           $data['subjects'] = DB::table('subjects')
              ->select('subjects.*', 'education_levels.name as education_level_name')
              ->leftJoin('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')
              ->leftJoin('education_levels', 'subject_education_levels.education_level_id', '=', 'education_levels.id')
              ->whereNull('subjects.deleted_at')
              ->get();

          $data['assignedSubjects'] = ClastreamSubject::with('subject')
              ->select('class_id', 'stream_id', 'subject_id')
              ->get()
              ->groupBy(['class_id', 'stream_id', 'subject_id']);

          
          return view('configurations.academic.subjects_allocation.index')->with($data);
      }


    public function monoUpdate(Request $req){



        // return $req->all();

        // $subject_id = $req->
        $class_id = $req->class_id;
        $stream_id = $req->stream_id;
        $subject_id = $req->subject_id;

        $grant = intval($req->grant);
        if ($grant) {

            $create = ClastreamSubject::create([
                'subject_id' => $subject_id,
                'class_id' => $class_id,
                'stream_id' => $stream_id,
                ]);

                }
        else{
           $delete = ClastreamSubject::where(['class_id'=>$class_id, 'stream_id'=>$stream_id, 'subject_id'=>$subject_id])->first()->delete();
        }

        $data = ['state'=>'Done','msg'=>'Subject Allocated', 'title'=>'success'];
        return response($data);

    }




      public function classWiseStreamLoad(Request $req){

        $elevel = SchoolClass::find($req->class_id)->education_level_id;
        $data['all_subjects'] = $all_subjects =  SubjectEducationLevel::join('subjects','subjects.id','=','subject_education_levels.subject_id')->where('education_level_id',$elevel)->get();

       $data['students'] = $student = Student::with('subjectsAssignments.subject')->where('class_id', $req->class_id)
       ->where('stream_id', $req->stream_id)->get();

        $view = view('student-management.allocation',$data)->render();
        return response(['html'=>$view]);


      }


}
