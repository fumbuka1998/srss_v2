<?php

namespace App\Http\Controllers\configurations\assign_classes;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassesAssignmentController extends Controller
{



    public function index(){

         $data['classes'] = SchoolClass::all();
         $data['academic_years'] = AcademicYear::all();
        $data['streams'] = Stream::all();
        $data['teachers'] = User::join('user_has_roles','user_has_roles.user_id','=','users.id')
                        ->select('users.id as teacher_id','users.firstname','users.lastname')
                        ->join('roles','roles.id','=','user_has_roles.role_id')
                        ->where('roles.type','teacher')->get();
        return view('configurations.academic.class-teachers.index')->with($data);


    }


    public function datatable(Request $request){


        try {


       $class_teachers = ClassTeacher::join('users','users.id','=','class_teachers.teacher_id')
                                     ->join('school_classes','school_classes.id','=','class_teachers.class_id')
                                     ->join('streams','class_teachers.stream_id','=','streams.id')
                                     ->select('school_classes.name as class_name','streams.name as stream_name','users.firstname','users.lastname','class_teachers.uuid as ctcuuid')
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

         return DataTables::of($class_teachers)

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

         ->editColumn('created_by',function($teacher){
            $user = User::find($teacher->created_by);
            return $user ? $user->full_name : 'admin';
         })

         ->addColumn('action',function($teacher){
             return
             '<span>
             <button type="button" data-uuid="'.$teacher->ctcuuid.'" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$teacher->ctcuuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
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

            $classTeachers = ClassTeacher::updateOrCreate(
                [

                    'uuid' =>$req->uuid

                ],

                [

                'class_id'=>$req->class_id,
                'stream_id'=>$req->stream,
                'uuid'=>generateUuid(),
                'teacher_id'=>$req->teacher,
                'level_flag' => $req->level,
                'academic_year_id' => $req->ac_year,
                'created_by'=>auth()->user()->id

               ]);

                 /* here user has roles */

               if ($classTeachers) {

                    UserHasRoles::updateOrCreate(
                        [
                            'role_id'=>12,
                            'user_id'=>$req->teacher
                        ]

                    );

                return response(['state'=>'done', 'msg'=> 'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $class = ClassTeacher::where('uuid',$uuid)->first();
        $destroy = $class->delete();

        //Remove from Class Duties ... to be done

        // UserHasRoles::updateOrCreate(
        //     [
        //         'role_id'=>12,
        //         'user_id'=>$req->teacher
        //     ]

        // );



        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){


        $classes  = ClassTeacher::where('uuid',$req->uuid)->first();
        return response($classes);



      }


      public function assign($uuid){

        $class = SchoolClass::where('uuid',$uuid)->first();
        $data['streams'] = $class->streams;
        return view('configurations.academic.class-teachers.assignment')->with($data);

      }

      public function getStreams(Request $req){

       $streams =  Stream::where('class_id',$req->id)->get();

        $streams_html = '<option> </option>';

        foreach ($streams as $key => $stream) {

        $streams_html .= '<option value="'.$stream->id.'"> '.$stream->name.' </option>';

     }

     return response($streams_html);





    }





}
