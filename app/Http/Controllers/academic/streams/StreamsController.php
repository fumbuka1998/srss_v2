<?php

namespace App\Http\Controllers\academic\streams;

use App\Http\Controllers\Controller;
use App\Models\ClastreamSubject;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StreamsController extends Controller
{
    //


    public function index(){

        $data['classes'] = SchoolClass::all();
        return view('configurations.academic.streams.streams')->with($data);

    }

    public function datatable(Request $request){


        try {

    //    return $streams = Stream::query()-get();
        $streams = Stream::all();


          $search = $request->get('search');

                if(!empty($search)){

            //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
            //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
            //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
            //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
            //    }

                    // $invoices = $invoices->groupBy('invoices.id');
                }

         return DataTables::of($streams)
         ->addColumn('name', function($stream){
            return $stream->name;
          
         })
         ->addColumn('code', function($stream){
            return $stream->code;
          
         })
         ->addColumn('capacity', function($stream){
            return $stream->capacity;
          
         })

         ->editColumn('class_id', function($stream){
            // return "under construction";
            return $stream->classes->name;
            // return $stream->class_id;

         })

         ->editColumn('created_by',function($stream){

            $user = User::find($stream->created_by);
            return $user ? $user->full_name : 'admin';
         })

         ->addColumn('action',function($stream){
             return '<span>

              <button type="button" data-uuid="'.$stream->uuid.'" class="btn btn-custon-four btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$stream->uuid.'" type="button" disabled class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action'])
       ->make(true);

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }



     public function store(Request $req){

        try {

            $capacity = $req->capacity;
                $stream = Stream::updateOrCreate(
                    [
                      'uuid' => $req->uuid
                    ],

                    [
                    'name'=>$req->name,
                    'class_id'=>$req->class,
                    'capacity'=> $capacity,
                    'uuid'=>generateUuid(),
                    'code'=>$req->code,
                    'created_by'=>1

                   ]);

               if ($stream) {

                return response(['state'=>'done', 'msg'=> 'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }


     public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $class = Stream::where('uuid',$uuid)->first();
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

        $stream  = Stream::where('uuid',$req->uuid)->first();
        return response($stream);

      }


}
