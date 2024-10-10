<?php

namespace App\Http\Controllers\configurations\assign_subjects;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubjectsAssignmentController extends Controller
{


    public function index(){

        $data['classes'] = SchoolClass::all();
        $data['ac_years'] = AcademicYear::all();
        $data['streams'] = Stream::all();
        $data['subjects'] = Subject::all();
        $data['teachers'] = User::join('user_has_roles','user_has_roles.user_id','=','users.id')
                        ->select('users.id as teacher_id','users.firstname','users.lastname')
                        ->join('roles','roles.id','=','user_has_roles.role_id')
                        ->where('roles.type','teacher')->get();
        return view('configurations.academic.subjectTeachers.index')->with($data);


    }


    public function datatable(Request $request){


        try {


      $subjectTeachers = SubjectTeacher::join('users','users.id','=','subject_teachers.teacher_id')
                                     ->join('school_classes','school_classes.id','=','subject_teachers.class_id')
                                     ->join('subjects','subjects.id','=','subject_teachers.subject_id')
                                     ->join('streams','subject_teachers.stream_id','=','streams.id')
                                     ->select('school_classes.name as class_name','subject_teachers.created_by','subjects.name as subject_name','streams.name as stream_name','users.firstname','users.lastname','subject_teachers.uuid as ctcuuid')
                                     ->get();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($subjectTeachers)

         ->addColumn('teacher',function($teacher){

             return $teacher->firstname. ' '.$teacher->lastname ;

             // return '<a href="'.route('students.profile',$invoice->student_name->student_id).'" > '.$invoice->student_name->first_name .' '. $invoice->student_name->last_name.'</a>';
         })


         ->addColumn('class',function($teacher){

            return $teacher->class_name;

            // return '<a href="'.route('students.profile',$invoice->student_name->student_id).'" > '.$invoice->student_name->first_name .' '. $invoice->student_name->last_name.'</a>';
        })


        ->addColumn('stream',function($teacher){

            return $teacher->stream_name;

        })

        ->addColumn('subjects',function($teacher){

            return $teacher->subject_name;

        })

         ->editColumn('created_by',function($teacher){
            $user = User::find($teacher->created_by);
            return $user ? $user->full_name : 'admin';
         })

         ->addColumn('action',function($teacher){
             return
             '<span>
             <button type="button" data-uuid="'.$teacher->ctcuuid.'" class="btn btn-custon-four btn-info btn-xs edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$teacher->ctcuuid.'" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i> Revoke Allocation</button>
         </span>';

         })

       ->rawColumns(['action'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store(Request $req){

        try {


           $subject = $req->subject;

                    $subjectTeachers = SubjectTeacher::updateOrCreate(
                        [

                            'uuid' =>$req->uuid

                        ],

                        [

                        'class_id'=>$req->class_id,
                        'stream_id'=>$req->stream,
                        'subject_id'=>$subject,
                        'uuid'=>generateUuid(),
                        'teacher_id'=>$req->teacher,
                        'academic_year_id'=> $req->ac_year,
                        'created_by'=>auth()->user()->id

                       ]);



               if ($subjectTeachers) {

                    UserHasRoles::updateOrCreate(
                        [
                            'role_id'=>11,
                            'user_id'=>$req->teacher
                        ]

                    );

                return response(['state'=>'done', 'msg'=> 'success','title'=>'success']);

               }

               return response(['state'=>'fail', 'msg'=> 'An error occured','title'=>'fail']);


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $class = SchoolClass::where('uuid',$uuid)->first();
        $destroy = $class->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'successfully deleted','title'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){


        $subjectTeachers  = subjectTeacher::where('uuid',$req->uuid)->first();
        return response($subjectTeachers);


      }


      public function assign($uuid){

        $class = SchoolClass::where('uuid',$uuid)->first();
        $data['streams'] = $class->streams;
        return view('configurations.academic.class-teachers.assignment')->with($data);

      }

      public function getStreams(Request $req){

        $streams =  Stream::where('class_id',$req->id)->get();
        $elevelid =  SchoolClass::find($req->id)->educationLevels->id;

        $subject_links = SchoolClass::with('educationLevels.subjectEducationLevels.subjects')->where('education_level_id',$elevelid)->get();

        $subjects = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
                            ->join('education_levels','education_levels.id','=','subject_education_levels.education_level_id')
                            ->select('subjects.name as sbjct_name','subjects.id as sbjct_id')
                            ->where('education_level_id',$elevelid)
                            ->get();

         $subjects_html = '<option> </option>';

         foreach ($subjects as $key => $subject) {

            $subjects_html .= '<option value="'.$subject->sbjct_id.'"> '.$subject->sbjct_name.' </option>';

         }

        $streams_html = '<option> </option>';

        foreach ($streams as $key => $stream) {

        $streams_html .= '<option value="'.$stream->id.'"> '.$stream->name.' </option>';


     }

     $data['streams_html'] = $streams_html;
     $data['subjects_html'] = $subjects_html;

     return response($data);


    }



}
