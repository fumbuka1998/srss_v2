<?php

namespace App\Http\Controllers\academic\grades;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\Grade;
use App\Models\GradeGroup;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use PHPUnit\TextUI\XmlConfiguration\Group;
use Yajra\DataTables\DataTables;

class GradesController extends Controller
{
    //
    public function index($uuid)
    {

        $data['education_levels'] = EducationLevel::all();
        $data['group_uuid'] = $uuid;
        return view('configurations.academic.grades.grades')->with($data);
    }

    public function datatable(Request $request)
    {
        // return $request;

        try {

            $gradeGroup = GradeGroup::where('uuid', $request->group_uuid)->first();
            $grades = $gradeGroup->grades;
            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($grades)

                ->editColumn('education_level_id', function ($grade) {

                    return '<span class="status_button"> ' . $grade->eLevels->name . ' </span>';;
                })

                ->editColumn('created_by', function ($grade) {

                    $user = User::find($grade->created_by);
                    return $user ? $user->full_name : 'admin';
                })

                ->addColumn('action', function ($class) {
                    return '<span>
             <button type="button" data-uuid="' . $class->uuid . '" class="btn btn-custon-four btn-info btn-xs edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="' . $class->uuid . '" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function store(Request $req)
    {
        // dd($req);

        try {

            $group_id = GradeGroup::where('id', $req->group_uuid)->first()->id;

            $grade = Grade::updateOrCreate(
                [
                    'uuid' => $req->uuid
                ],

                [
                    'name' => $req->name,
                    'education_level_id' => $req->education_level_id,
                    'from' => $req->from,
                    'to' => $req->to,
                    'remarks' => $req->remarks,
                    'group_id' => $group_id,
                    'uuid' => generateUuid(),
                    'points' => $req->points,
                    'created_by' => auth()->user()->id

                ]
            );

            if ($grade) {

                return response(['state' => 'done', 'msg' => 'success', 'title' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function destroy(Request $req)
    {

        try {
            $uuid = $req->uuid;
            $smstr = Grade::where('uuid', $uuid)->first();
            $destroy = $smstr->delete();

            if ($destroy) {

                return response(['state' => 'done', 'msg' => 'success', 'title' => 'success']);
                # code...
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function edit(Request $req)
    {

        $grade  = Grade::where('uuid', $req->uuid)->first();
        return response($grade);
    }





    public function gradeGroups()
    {

        $data['education_levels'] = EducationLevel::all();
        return view('configurations.academic.grades.grade_groups')->with($data);
    }




    public function groupDatatable(Request $request)
    {


        try {


            $grades_groups = GradeGroup::all();

            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($grades_groups)

                ->editColumn('created_by', function ($grade) {

                    $user = User::find($grade->created_by);
                    $full_name = $user ? $user->full_name : 'admin';
                    return $full_name;
                })

                ->addColumn('linked_grades', function ($grade) {
                    return $grade->grades->count();
                })

                ->addColumn('action', function ($grade) {
                    return '<span>
             <a style="color:#fff" href="' . route('academic.grades.index', $grade->uuid) . '" type="button" class="btn btn-custon-four btn-info btn-xs"><i class="fa fa-eye"></i></a>
             | <button type="button" data-uuid="' . $grade->uuid . '" class="btn btn-custon-four btn-primary btn-xs edit"><i class="fa fa-edit"></i></button>
             | <button data-uuid="' . $grade->uuid . '" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
         </span>';
                })

                ->rawColumns(['action'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function groupStore(Request $req)
    {

        try {

            // return $req->all();

            $grade = GradeGroup::updateOrCreate(
                [
                    'uuid' => $req->uuid
                ],
                [
                    'name' => $req->name,
                    'uuid' => generateUuid(),
                    'created_by' => auth()->user()->id
                ]
            );

            if ($grade) {

                return response(['state' => 'done', 'msg' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function groupDestroy(Request $req)
    {

        try {
            $uuid = $req->uuid;
            $smstr = GradeGroup::where('uuid', $uuid)->first();
            $destroy = $smstr->delete();

            if ($destroy) {

                return response(['state' => 'done', 'msg' => 'success']);
                # code...
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function groupEdit(Request $req)
    {

        $grade  = GradeGroup::where('uuid', $req->uuid)->first();
        return response($grade);
    }
}
