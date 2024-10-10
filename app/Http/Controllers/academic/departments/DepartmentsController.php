<?php

namespace App\Http\Controllers\academic\departments;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\EducationLevel;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DepartmentsController extends Controller
{
    //


    public function index(){
        $data['hods'] = User::all();
        return view('configurations.academic.departments.departments')->with($data);

    }


    public function store(Request $req){
      

        try {

            
            $department = Department::updateOrCreate(
                [
                  'uuid' => $req->uuid
                ],

                [

                'name'=>$req->name,
                'uuid'=>generateUuid(),
                'code'=>$req->code,
                'created_by'=>auth()->user()->id,
                'hod_id'=>$req->hod_id

               ]);

               if ($department) {

                return response(['state'=>'done', 'msg'=> 'success', 'title'=>'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }

    public function datatable(Request $request){


        try {


       $departments = Department::all();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($departments)
            ->editColumn('hod_id', function ($department) {
                $hod = User::find($department->hod_id);
                return $hod ? $hod->firstname . ' '. $hod->lastname : '';
            })

         ->editColumn('created_by',function($dept){

             $user = User::find($dept->created_by);
            return $user ? $user->full_name : 'admin';
         })

         ->addColumn('action',function($class){
             return '<span>

             <button type="button" data-uuid="'.$class->uuid.'" class="btn btn-custon-four btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$class->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }



     public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $department = Department::where('uuid',$uuid)->first();
        $destroy = $department->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }



    public function edit(Request $req){

      $department  = Department::where('uuid',$req->uuid)->first();
      return response($department);

    }
}


