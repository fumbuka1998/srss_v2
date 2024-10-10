<?php

namespace App\Http\Controllers\students_management;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClastreamSubject;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentSubjectsAssignment;
use App\Models\Subject;
use App\Models\SubjectEducationLevel;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssignStudentSubjects extends Controller
{

    public $global;
    public function __construct(){

        $this->global = new GlobalHelpers();

    }

    public function index($uuid){


        $data['student'] = $student = Student::leftjoin('school_classes','students.class_id','=','school_classes.id')
        ->leftjoin('streams','students.stream_id','=','streams.id')
        ->select('students.religion_id','students.id','students.stream_id','students.class_id','students.religion_sect_id','students.firstname','students.middlename','students.dob','students.uuid as student_uuid', 'students.lastname','streams.name as stream_name','streams.id as str_id','school_classes.name as class_name','students.profile_pic')
        ->where('students.uuid',$uuid)->first();

        $class_id =  $student->class_id;  $stream_id =  $student->stream_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;
        $data['assignedSubjects'] = $assignedSubjects = StudentSubjectsAssignment::join('subjects','student_subjects_assignments.subject_id','=','subjects.id')
                                                   ->join('school_classes','school_classes.id','=','student_subjects_assignments.class_id')
                                                   ->join('streams','streams.id','=','student_subjects_assignments.stream_id')
                                                   ->select('school_classes.name as class_name','student_subjects_assignments.subject_id','subjects.name as subject_name','student_subjects_assignments.id as the_id','student_subjects_assignments.subject_id')
                                                   ->where('student_id',$student->id)->get();




      $data['subjects'] = ClastreamSubject::where(['clastream_subjects.class_id'=>$class_id,'clastream_subjects.stream_id'=>$stream_id])->join('school_classes','school_classes.id','=','clastream_subjects.class_id')
                                                                                                 ->join('streams','streams.id','=','clastream_subjects.stream_id')
                                                                                                 ->join('subjects','subjects.id','=','clastream_subjects.subject_id')
                                                                                                 ->select('subjects.name','subjects.id')->get();


    //    SubjectEducationLevel::join('subjects','subjects.id','=','subject_education_levels.subject_id')
    //                                       ->select('subjects.name as subject_name','subjects.id as subject_id')
    //                                       ->where('education_level_id',$elevel)->get();

        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = SchoolClass::all();
        $data['classes'] =  $class_streams = SchoolClass::leftjoin('streams','streams.class_id','=','school_classes.id')
                                                                ->select('streams.id as stream_id','school_classes.id as class_id','school_classes.name as class_name','streams.name as stream_name') ->get();
        $data['class_streams']= array();
        $data['activeTab'] = 'allocatedSbjctsTab';

        $data['class'] = SchoolClass::find($student->class_id);
        $data['stream'] = Stream::find($student->stream_id);

        $data['imageUrl'] = '';
    if ($student->profile_pic) {
        $data['imageUrl'] = asset('storage/' . $student->profile_pic);
    }

    $data['age'] =  $this->global->ageCalculator($student->dob);


        foreach ($class_streams as $key => $xs) {
            $data['class_streams'][$key]['name'] = $xs->class_name. ' '. $xs->stream_name;
            $data['class_streams'][$key]['class_id'] = $xs->class_id;
            $data['class_streams'][$key]['stream_id'] = $xs->stream_id;
        }

        $csts = $data['class_streams'];
        $data['csts'] = json_encode($csts);  

        return view('student-management.graphy.assigned_subjects')->with($data);

    }



    public function datatable(Request $request,$uuid){


        $index = 1;
        try {


      $student_id = Student::where('uuid',$uuid)->first()->id;
      $assignedSubjects = StudentSubjectsAssignment::join('subjects','student_subjects_assignments.subject_id','=','subjects.id')
                                                 ->join('school_classes','school_classes.id','=','student_subjects_assignments.class_id')
                                                 ->join('streams','streams.id','=','student_subjects_assignments.stream_id')
                                                 ->select('school_classes.name as school_name','student_subjects_assignments.subject_id','subjects.name as subject_name','student_subjects_assignments.id as the_id','student_subjects_assignments.subject_id')
                                                 ->where('student_id',$student_id);

        $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($assignedSubjects)

         ->editColumn('subject_id',function($subjct){
             return $subjct->subject_name;
         })

         ->addColumn('index', function() use (&$index) {
            return $index++;
        })

         ->addColumn('action',function($ex){
             return '<span>
         <button type="button" data-uuid="'.$ex->uuid.'" class="btn btn-custon-four btn-primary btn-xs edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$ex->uuid.'" type="button" disabled class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action','status'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store(Request $req,$uuid){

        try {

            DB::beginTransaction();
            // return $req->all();

            $student_id = Student::where('uuid', $uuid)->first()->id;

            $submittedSubjects = $req->subjects;

            $assignedSubjects = StudentSubjectsAssignment::where('student_id', $student_id)->pluck('subject_id')->toArray();

            // Find the subjects to be added and removed
            $subjectsToAdd = array_diff($submittedSubjects, $assignedSubjects);
            $subjectsToRemove = array_diff($assignedSubjects, $submittedSubjects);

            if ($subjectsToAdd) {

               foreach ($subjectsToAdd as $subject) {
                $sbjct =    StudentSubjectsAssignment::create([
                    'uuid' => generateUuid(),
                    'subject_id' => $subject,
                    'student_id' => $student_id,
                    'class_id' => $req->class_id,
                    'stream_id' => $req->stream_id,
                    ]);
                    }

            }


            foreach ($subjectsToRemove as $subject) {

           $sbjct =  StudentSubjectsAssignment::where('student_id', $student_id)
            ->where('subject_id', $subject)
            ->delete();

            }

            DB::commit();

            $updatedCount =  count(StudentSubjectsAssignment::where('student_id', $student_id)->pluck('subject_id')->toArray());

            if ($sbjct) {

                return response(['state'=>'done', 'msg'=> 'Subject Allocated!','title'=>'success','updated_count'=> $updatedCount]);

            }

            return response(['state'=>'Fail', 'msg'=> 'Ooops','title'=>'fail']);


        } catch (QueryException $e) {

            DB::rollback();
            return $e->getMessage();

        }
    }

    public function monoUpdate_tareck(Request $req){



        // return $req->all();

        // $subject_id = $req->
        $class_id = $req->class_id;
        $stream_id = $req->stream_id;
        $subject_id = $req->subject_id;
        $student_id = $req->student_id;

        $grant = intval($req->grant);
        if ($grant) {

            $create = StudentSubjectsAssignment::create([
                'uuid' => generateUuid(),
                'subject_id' => $subject_id,
                'student_id' => $student_id,
                'class_id' => $class_id,
                'stream_id' => $stream_id,
                ]);
                }
        else{
           $delete = StudentSubjectsAssignment::where(['class_id'=>$class_id, 'student_id'=>$student_id, 'stream_id'=>$stream_id, 'subject_id'=>$subject_id])->first()->delete();
        }

        $data = ['state'=>'done','msg'=>'subject allocated', 'title'=>'success'];
        return response($data);

    }

    // improved method
    public function monoUpdate(Request $req){
        // return $req->all();

        // $subject_id = $req->
        $class_id = $req->class_id;
        $stream_id = $req->stream_id;
        $subject_id = $req->subject_id;
        $student_id = $req->student_id;

        $grant = intval($req->grant);
        if ($grant) {

            // $create = StudentSubjectsAssignment::create([
            //     'uuid' => generateUuid(),
            //     'subject_id' => $subject_id,
            //     'student_id' => $student_id,
            //     'class_id' => $class_id,
            //     'stream_id' => $stream_id,
            //     ]);
            //     }

            $create = StudentSubjectsAssignment::updateOrCreate(
                [
                    'subject_id' => $subject_id,
                    'student_id' => $student_id,
                    'class_id' => $class_id,
                    'stream_id' => $stream_id,
                ],
                [
                    'uuid' => generateUuid(),

                ]
            );
        }
        else{
            // return "hapa";
           $delete = StudentSubjectsAssignment::where(['class_id'=>$class_id, 'student_id'=>$student_id, 'stream_id'=>$stream_id, 'subject_id'=>$subject_id])->first()->delete();

           if($delete){
            $data = ['state'=>'done','msg'=>'subject deleted', 'title'=>'success'];
            return response($data);
           }
        }

        $data = ['state'=>'done','msg'=>'subject allocated', 'title'=>'success'];
        return response($data);

    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $class = SchoolClass::where('uuid',$uuid)->first();
        $destroy = $class->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success','title'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){


        $classes  = SchoolClass::where('uuid',$req->uuid)->first();
        return response($classes);



      }






      /* another one ----DJ KHALEEEED */

      public function classWiseStreamIndex(){

        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = SchoolClass::all();
        $data['class_streams']= array();
        $data['activeTab'] = 'assigned_subjects';

        $csts = $data['class_streams'];
        $data['csts'] = json_encode($csts);

        return view('student-management.subjects_allocation')->with($data);



      }

      public function classWiseStreamLoad(Request $req){

        $class_id = $req->class_id;
        $stream_id = $req->stream_id;

        $data['all_subjects'] = ClastreamSubject::where(['clastream_subjects.class_id'=>$class_id,'clastream_subjects.stream_id'=>$stream_id])->join('school_classes','school_classes.id','=','clastream_subjects.class_id')
        ->join('streams','streams.id','=','clastream_subjects.stream_id')
        ->join('subjects','subjects.id','=','clastream_subjects.subject_id')
        ->select('subjects.name','subjects.id')->get();

       $data['students'] = $student = Student::with('subjectsAssignments.subject')->where('class_id', $req->class_id)
       ->where('stream_id', $req->stream_id)->get();

        $view = view('student-management.allocation',$data)->render();
        return response(['html'=>$view]);


      }



}
