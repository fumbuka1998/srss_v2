<?php

namespace App\Http\Controllers\academic\subjects;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\EducationLevel;
use App\Models\Subject;
use App\Models\SubjectEducationLevel;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubjectsController extends Controller
{
    //
    public function index(){

        $data['elevels'] = EducationLevel::all();
        $data['departments'] = Department::all();
        return view('configurations.academic.subjects.subjects')->with($data);

    }



    public function datatable(Request $request){


        try {



       $subjects = Subject::with('subjectEducationLevels.educationlevels')->get();

          $search = $request->get('search');

        if(!empty($search)){

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($subjects)


         ->editColumn('education_level_id', function($subject){
            $acl = '';
            $elevel = '';
            $classes = ['badge-info','badge-success','badge-danger'];
            foreach ($subject->subjectEducationLevels as $key => $sbjclevel) {
               $elevel.= '<span class="badge '.$classes[$key].' mt-1 ml-2"> '.$sbjclevel->educationlevels->name.'  </span>';
            }
            return $elevel;

         })

         ->editColumn('department_id', function($subject){
            $department = $subject->department ? $subject->department->name : '';
            return $department;

         })


         ->editColumn('created_by',function($subject){
            $user = User::find($subject->created_by);
            $full_name = $user ? $user->full_name : 'admin';
             return $full_name;
         })

         ->addColumn('action',function($subject){
             return '<span>

             <button type="button" data-uuid="'.$subject->uuid.'" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button disabled data-uuid="'.$subject->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action','education_level_id'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store_tq(Request $req){

        try {

            // return $req->all();

              $subject = Subject::updateOrCreate(
                [
                  'uuid' => $req->uuid
                ],

                [
                'name'=>$req->name,
                'department_id'=>$req->department,
                'subject_type'=>$req->course_type,
                'points'=>$req->points,
                'uuid'=>generateUuid(),
                'code'=>$req->code,
                'created_by'=> auth()->user()->id

               ]);

               if ($subject) {

                foreach ($req->elevels as $key => $elevel) {
                    SubjectEducationLevel::updateOrcreate(
                        [
                          'education_level_id'=>$elevel,
                          'subject_id'=>$subject->id
                        ],
                        [
                        'education_level_id'=>$elevel,
                        'subject_id'=>$subject->id
                        ],

                    );

                }

               }

               if ($subject) {

                return response(['state'=>'done', 'msg'=> 'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }

    // improved method by me

    public function store(Request $req)
    {
        try {
            
            $subject = Subject::updateOrCreate(
                ['uuid' => $req->uuid],
                [
                    'name' => $req->name,
                    'department_id' => $req->department,
                    'subject_type' => $req->course_type,
                    'points' => $req->points,
                    'code' => $req->code,
                    'created_by' => auth()->user()->id,
                ]
            );

            // Get the subject ID from the request 
            $subjectId = $subject->id;

            // Delete existing SubjectEducationLevel
            SubjectEducationLevel::where('subject_id', $subjectId)->delete();

        
            if ($subject) {
                foreach ($req->elevels as $elevel) {
                    // Log::info("Processing elevel: $elevel for subject ID: $subject->id");

                    SubjectEducationLevel::updateOrCreate(
                        [
                            'education_level_id' => $elevel,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'education_level_id' => $elevel,
                            'subject_id' => $subject->id,
                        ]
                    );
                }
            }

            return response(['state' => 'done', 'msg' => 'success']);
        } catch (\Exception $e) {
            return response(['state' => 'error', 'msg' => $e->getMessage()]);
        }
    }




     public function destroy(Request $req){
        try {
        $uuid = $req->uuid;
        $class = Subject::where('uuid',$uuid)->first();
        $destroy = $class->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){

        $subjects  = Subject::with('subjectEducationLevels.educationlevels')->where('uuid',$req->uuid)->get();
        return response($subjects);

      }



}
