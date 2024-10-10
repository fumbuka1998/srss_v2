<?php

namespace App\Http\Controllers\academic\years;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\User;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class YearsController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers;
    }

    public function index()
    {

        return view('configurations.academic.year.year');
    }


    public function store(Request $req)
    {

        try {



            $acdmic_year = AcademicYear::updateOrCreate(

                [

                    'uuid' => $req->uuid
                ],

                [
                    // $this->global->toMysqlDateFormat($req->end)

                    'name' => $req->academic_year,
                    'from' => $req->start,
                    'to' => $req->end,
                    'uuid' => generateUuid(),
                    'status' => $req->status,
                    'created_by' => auth()->user()->id

                ]
            );

            if ($acdmic_year) {

                return response(['state' => 'done', 'msg' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function destroy(Request $req)
    {

        try {
            $uuid = $req->uuid;
            $academic_year = AcademicYear::where('uuid', $uuid)->first();
            $destroy = $academic_year->delete();

            if ($destroy) {

                return response(['state' => 'done', 'msg' => 'success']);
                # code...
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function edit(Request $req)
    {

        $year  = AcademicYear::where('uuid', $req->uuid)->first();
        $to = $year->to;
        $from = $year->from;
        $data['year'] = $year;
        $data['from'] = $from;
        $data['to'] = $to;
        return response($data);
    }

    public function datatable(Request $request)
    {
        try {
            $academic_years = AcademicYear::all();

            //logic to auto update the academic year status
            foreach ($academic_years as $academic) {
                $today = now();
                $end_date = Carbon::parse($academic->to);

                // Check if the academic year should be closed
                if ($today->greaterThan($end_date) && $academic->status != 'closed') {
                    $academic->status = 'closed';
                    $academic->save();
                }
            }



            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($academic_years)

                ->editColumn('start', function ($academic) {

                    return date("d M, Y", strtotime($academic->from));
                })


                ->editColumn('created_by', function ($academic) {

                    $user = User::find($academic->created_by);
                    return $user ? $user->full_name : 'admin';
                })



                ->editColumn('end', function ($academic) {

                    return date("d M, Y", strtotime($academic->to));
                })

                ->editColumn('status', function ($academic) {
                    return  '<span class="status_button"> ' . $academic->status . ' </span>';
                })

                ->addColumn('action', function ($academic) {
                    return '<span>
              <button type="button" data-uuid="' . $academic->uuid . '" class="btn  btn-info btn-sm edit"><i class="fa fa-edit edit"></i></button>
             | <button data-uuid="' . $academic->uuid . '" type="button" class="btn  btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'status'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }
}
