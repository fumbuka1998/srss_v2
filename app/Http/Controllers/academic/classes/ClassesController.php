<?php

namespace App\Http\Controllers\academic\classes;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\EducationLevel;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClassesController extends Controller
{
    // 

    public function index(){ 
        $data['education_levels'] = EducationLevel::all();
        return view('configurations.academic.classes.classes')->with($data);

    }


    public function datatable(Request $request){


        try {


       $classes = SchoolClass::all();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($classes)

         ->addColumn('name',function($class){

             return $class->name;

             // return '<a href="'.route('students.profile',$invoice->student_name->student_id).'" > '.$invoice->student_name->first_name .' '. $invoice->student_name->last_name.'</a>';
         })

         ->editColumn('education_level_id', function($class){

           return '<button style="padding: 2px 5px;border-radius: 5px;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> '.$class->educationLevels->name.' </button> &nbsp;';



         })

         ->editColumn('created_by',function($class){

            $user = User::find($class->created_by);
            return $user ? $user->full_name : 'admin';
         })

         ->addColumn('action',function($class){
             return '<span>
             <button type="button" class="btn btn-info btn-sm "><i class="fa fa-eye"></i></button>
             | <button type="button" data-uuid="'.$class->uuid.'" class="btn btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$class->uuid.'" type="button" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action','education_level_id'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store(Request $req){

        try {

            $classes = SchoolClass::updateOrCreate(
                [

                    'uuid' =>$req->uuid

                ],

                [

                'name'=>$req->name,
                'education_level_id'=>$req->education_level_id,
                'uuid'=>generateUuid(),
                'capacity'=>$req->capacity,
                'created_by'=>auth()->user()->id

               ]);

               if ($classes) {

                return response(['state'=>'done', 'msg'=> 'success','title'=>'success']);


               }


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

            return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
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


}



