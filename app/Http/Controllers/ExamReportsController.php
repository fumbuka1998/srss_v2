<?php

namespace App\Http\Controllers;

use App\Models\ExamReport;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExamReportsController extends Controller
{
    public function index(){

        return view('configurations.academic.exam-reports.index');

    }

    public function datatable(Request $request){


        try {


       $education_levels = ExamReport::all();

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

         ->editColumn('created_by',function($exam){

            $user = User::find($exam->created_by);
            return $user ? $user->full_name : 'admin';
            
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
            $elevels = ExamReport::updateOrCreate(
                [
                    'uuid'    => $uuid
                ],

                [
                'name'=>$req->name,
                'uuid'=>generateUuid(),
                'code'=>$req->code,
                'created_by'=>auth()->user()->id

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
        $elevel = ExamReport::where('uuid',$uuid)->first();
        $destroy = $elevel->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success','title'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }

    public function edit(Request $req){


        $elevels  = ExamReport::where('uuid',$req->uuid)->first();
        return response($elevels);



      }
}
