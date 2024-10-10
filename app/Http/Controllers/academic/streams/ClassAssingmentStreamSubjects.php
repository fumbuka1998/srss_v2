<?php

namespace App\Http\Controllers\academic\streams;

use App\Http\Controllers\Controller;
use App\Models\ClastreamSubject;
use App\Models\SchoolClass;
use App\Models\Stream;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClassAssingmentStreamSubjects extends Controller
{




    public function assignSubjects(){
        $data['classes'] = SchoolClass::all();
        return view('configurations.academic.streams.subjects_assignment')->with($data);
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



      public function assignedSubjectsDatatable(Request $request){


        try {

       $subjects = ClastreamSubject::join('streams','streams.id','=','clastream_subjects.stream_id')
                                    ->join('school_classes','clastream_subjects.class_id','=','school_classes.id')
                                    ->join('subjects','subjects.id','=','clastream_subjects.subject_id')
                                    ->select('school_classes.name as class_name','streams.name as stream_name','subjects.name as sbjct_name');

          $search = $request->get('search');

        if(!empty($search)){

        }

         return DataTables::of($subjects)

         ->editColumn('class_id', function($stream){

            return $stream->classes->name;

         })

         ->editColumn('stream_id',function($stream){

             return $stream->stream_name;
         })

         ->editColumn('subject_id',function($stream){

            return $stream->subject_name;
        })

         ->addColumn('action',function($stream){
             return '<span>
             <button type="button" class="btn btn-custon-four btn-info btn-xs"><i class="fa fa-eye"></i></button>
             | <button type="button" data-uuid="'.$stream->uuid.'" class="btn btn-custon-four btn-primary btn-xs edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$stream->uuid.'" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
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
        $class = Stream::where('uuid',$uuid)->first();
        $destroy = $class->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success']);

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
