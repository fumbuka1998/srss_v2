<?php

namespace App\Http\Controllers\academic\semsters;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\User;
use DateTime;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SemestersController extends Controller
{
    //
    public $global;

    public function __construct(){

        $this->global = new GlobalHelpers;

    }

    public function index(){

        $data['years'] = AcademicYear::all();
        return view('configurations.academic.semesters.semesters')->with($data);

    }

    public function datatable(Request $request){


        try {


       $semesters = Semester::all();

          $search = $request->get('search');

        if(!empty($search)){

     //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
     //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
     //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
     //    }

             // $invoices = $invoices->groupBy('invoices.id');
        }

         return DataTables::of($semesters)

         ->editColumn('academic_year_id', function($semester){

            return $semester->academicYear->name;

         })

         ->editColumn('from', function($semester){

             return date("d M, Y", strtotime($semester->from));;

         })

         ->editColumn('to', function($semester){

            return date("d M, Y", strtotime($semester->to));

         })

         ->editColumn('duration', function($semester){

            return $semester->duration .' '.'days' ;

         })


         ->editColumn('status', function($semester){

            return '<span class="status_button"> '.$semester->status.' </span>';

         })

         ->editColumn('created_by',function($class){

            $user = User::find($class->created_by);
             return $user ? $user->full_name : 'admin';
         })

         ->addColumn('action',function($class){
             return '<span>
              <button type="button" data-uuid="'.$class->uuid.'" class="btn btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="'.$class->uuid.'" type="button" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';

         })

       ->rawColumns(['action','status'])
       ->make();

        } catch (QueryException $e) {

           return $e->getMessage();

        }

     }


     public function store(Request $req){

        try {

            $from = $this->global->toMysqlDateFormat($req->from);
            $to = $this->global->toMysqlDateFormat($req->to);

            $from_objt = new DateTime($from);
            $to_objct = new DateTime($to);

            $interval = $from_objt->diff(($to_objct));
            $days = $interval->days;

                $semester = Semester::updateOrCreate(
                    [
                      'uuid' => $req->uuid
                    ],

                    [
                    'name'=>$req->name,
                    'academic_year_id'=>$req->year,
                    'from'=> $from,
                    'to'=> $to,
                    'uuid'=>generateUuid(),
                    'duration'=>$days,
                    'status'=>$req->status,
                    'created_by'=>auth()->user()->id

                   ]);

               if ($semester) {

                return response(['state'=>'done', 'msg'=> 'success', 'title'=>'success']);


               }


        } catch (QueryException $e) {

            return $e->getMessage();

        }
    }



     public function destroy(Request $req){

        try {
        $uuid = $req->uuid;
        $smstr = Semester::where('uuid',$uuid)->first();
        $destroy = $smstr->delete();

        if ($destroy) {

            return response(['state'=>'done', 'msg'=>'success', 'title'=>'success']);
            # code...
        }

        } catch (QueryException $e) {

            return $e->getMessage();
        }

    }


    public function edit(Request $req){

        $semester  = Semester::where('uuid',$req->uuid)->first();
        $to = $semester->to;
        $from = $semester->from;
        $data['semester'] = $semester;
        $data['from'] = $from;
        $data['to'] = $to;
        return response($data);

      }
}
