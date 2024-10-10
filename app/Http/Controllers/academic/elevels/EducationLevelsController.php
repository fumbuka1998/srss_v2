<?php

namespace App\Http\Controllers\academic\elevels;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EducationLevelsController extends Controller
{
    //

    public function index(){

        return view('configurations.academic.education-levels.education');

    }

    public function datatable(Request $request){


        try {


       $education_levels = EducationLevel::all();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($education_levels)

         ->editColumn('created_by',function($class){

             return 'admin';
         })

         ->addColumn('action',function($elevel){
             return '<span>
              <button type="button" data-uuid="'.$elevel->uuid.'" class="btn btn-custon-four btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$elevel->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
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
            $uuid  = $req->uuid;
            $elevels = EducationLevel::updateOrCreate(
                [
                    'uuid'    => $uuid
                ],

                [
                'name'=>$req->name,
                'uuid'=>generateUuid(),
                'code'=>$req->code,
                'created_by'=> auth()->user()->id

               ]);

               if ($elevels) {

                return response(['state'=>'done', 'msg'=> 'success','title'=>'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


    public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $elevel = EducationLevel::where('uuid',$uuid)->first();
        $destroy = $elevel->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }

    public function edit(Request $req){


        $elevels  = EducationLevel::where('uuid',$req->uuid)->first();
        return response($elevels);



      }






}
