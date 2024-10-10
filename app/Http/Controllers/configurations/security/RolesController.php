<?php

namespace App\Http\Controllers\configurations\security;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    //

    public function index(){



        $data['activeTab'] = 'roles';
        return view('configurations.security.roles')->with($data);

    }

    // configurations.security.roles.permissions  // in the roles datatable

    public function datatable(Request $request){


        try {


       $roles = Role::all();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($roles)


         ->editColumn('created_by',function($role){

             return User::find($role->created_by) ? User::find($role->created_by)->full_name : 'admin';
         })

         ->addColumn('action',function($role){
             return '<span>
             <a type="button" style="color:white" href="'.route('configurations.security.roles.assignment.index',$role->uuid).'" data-uuid="'.$role->uuid.'" class="btn btn-custon-four btn-info btn-sm view"><i class="fa fa-eye"></i></a>
             | <button type="button" data-uuid="'.$role->uuid.'" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button disabled data-uuid="'.$role->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
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


            // return $req->all();


            $role = Role::updateOrCreate(
                [

                    'uuid' =>$req->uuid

                ],

                [

                'name'=>$req->name,
                'type'=>$req->type,
                'description'=>$req->description,
                'guard_name'=>'web',
                'uuid'=>generateUuid(),
                'created_by'=>1

               ]);

               if ($role) {

                return response(['state'=>'done', 'msg'=> 'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $role = Role::where('uuid',$uuid)->first();
        $destroy = $role->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){


        $role  = Role::where('uuid',$req->uuid)->first();
        return response($role);



      }


      public function assignPermissionsIndex($role_uuid){

        $role = Role::where('uuid',$role_uuid)->first();
        $data['permissions'] = Permission::all();
        $data['role_uuid'] = $role_uuid;

         $data['pms_assigned'] = $pms_assigned =   DB::table('role_has_permissions')
        ->where('role_id', $role->id)->get();

      $data['modules'] = Module::where('parent_id',null)->get();

        return view('configurations.security.assign_permissions_roles')->with($data);

      }


      public function assignPermissionStore(Request $req,$uuid){

        try {

            // return $req->all();

         $role = Role::where('uuid',$uuid)->first();
          $permissionId = $req->pmsd;


            if ($req->has('pmsd') && $req->grant == "true") {

                RoleHasPermission::create([
                    'role_id'=>$role->id,
                    'permission_id'=>$permissionId,
                    'module_id'=> $req->module_id
                ]);

                $data = ['state'=>'done', 'title'=>'success','msg'=>'Permission Granted'];
            }else{

                DB::table('role_has_permissions')
                ->where('role_id', $role->id)
                ->where('permission_id', $permissionId)
                ->delete();
                $data = ['state'=>'done', 'title'=>'success','msg'=>'Permission Revoked'];
            }

            return response($data);

        } catch (QueryException $e) {

            return $e->getMessage();

        }


      }




}
