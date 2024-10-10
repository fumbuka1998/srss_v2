<?php

namespace App\Http\Controllers\configurations\security;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PermissionsController extends Controller
{

    public function index(){

        $data['activeTab'] = 'permissions';
        return view('configurations.security.permissions')->with($data);

    }

    // configurations.security.roles.permissions  // in the roles datatable

    public function datatable(Request $request){


        try {


       $permissions = Permission::all();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($permissions)


         ->editColumn('created_by',function($role){

             return 'admin';
         })

         ->addColumn('action',function($role){
             return '<span>
            <button type="button" data-uuid="'.$role->uuid.'" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$role->uuid.'" type="button" disabled class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
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

            $permission = Permission::updateOrCreate(
                [

                    'uuid' =>$req->uuid

                ],

                [

                'name'=>$req->name,
                'uuid'=>generateUuid(),
                'created_by'=>auth()->user()->id

               ]);

               if ($permission) {

                return response(['state'=>'done', 'msg'=> 'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $permission = Permission::where('uuid',$uuid)->first();
        $destroy = $permission->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){


        $permission  = Permission::where('uuid',$req->uuid)->first();
        return response($permission);



      }
}
