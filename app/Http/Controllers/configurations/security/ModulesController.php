<?php

namespace App\Http\Controllers\configurations\security;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleHasPermission;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ModulesController extends Controller
{


    public function index(){
        $data['activeTab'] = 'modules';
        $data['modules'] = Module::all();
        $data['permissions'] = Permission::all();
        return view('configurations.security.modules')->with($data);

    }

    // configurations.security.roles.permissions  // in the roles datatable

    public function datatable(Request $request){


        try {


       $modules = Module::with('ModulePermissions.permissions');

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($modules)


         ->editColumn('created_by',function($module){

             return 'admin';
         })

         ->addColumn('parent',function($module){

            return $module->parent ? $module->parent->name : '';
        })

        ->addColumn('permissions',function($module){

            $acl = '';
            foreach ($module->ModulePermissions as $key => $modulePermission) {
                $acl.= '<span style="padding: 2px 5px;border-radius: 5px;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> '.$modulePermission->permissions->name.' </span> &nbsp;';
            }
            return $acl;
        })

         ->addColumn('action',function($module){
             return '<span>
             <button type="button" data-uuid="'.$module->uuid.'" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$module->uuid.'" disabled type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action','permissions'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store(Request $req){

        try {


            $permissions = $req->permissions;

            $module = Module::updateOrCreate(
                [

                    'uuid' =>$req->uuid

                ],

                [

                'name'=>$req->name,
                'parent_id'=>$req->parent,
                'uuid'=>generateUuid(),
                'created_by'=>1

               ]);

              $aa = ModuleHasPermission::where('module_id',$module->id)->first();
              if ($aa) {

                $aa->delete();

              }

               if ($permissions) {

                foreach ($permissions as $key => $permission) {

                    ModuleHasPermission::updateOrCreate(
                        [
                            'module_id'=>$module->id,
                            'permission_id'=>$permission
                        ],

                        [
                            'uuid'=>generateUuid(),
                        ]


                        );


                }


               }



               if ($module) {

                return response(['state'=>'done', 'msg'=> 'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $module = Module::where('uuid',$uuid)->first();
        $destroy = $module->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){


        $module  =  Module::with('ModulePermissions.permissions')->where('uuid',$req->uuid)->first();
        return response($module);



      }



}
