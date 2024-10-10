<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamScheduleClass;
use App\Models\ExamScheduleClassStream;
use App\Models\ExamScheduleClassStreamSubject;
use App\Models\ExamScheduleSubject;
use App\Models\ExamSubject;
use App\Models\Grade;
use App\Models\GradeGroup;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExamScheduleController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers;
    }

    public function index()
    {

        // return 'apa';
        $data['exam_subjects'] = ExamSubject::all();
        $data['grade_profiles'] = GradeGroup::all();
        $data['subjects'] = Subject::all();
        $data['semesters'] = Semester::all();
        $data['exam_types'] = Exam::all();
        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = SchoolClass::all();
        $data['classes'] =  $class_streams = SchoolClass::leftjoin('streams', 'streams.class_id', '=', 'school_classes.id')
            ->select('streams.id as stream_id', 'school_classes.id as class_id', 'school_classes.name as class_name', 'streams.name as stream_name')->get();
        $data['class_streams'] = array();

        foreach ($class_streams as $key => $xs) {
            $data['class_streams'][$key]['name'] = $xs->class_name . ' ' . $xs->stream_name;
            $data['class_streams'][$key]['class_id'] = $xs->class_id;
            $data['class_streams'][$key]['stream_id'] = $xs->stream_id;
        }

        $csts = $data['class_streams'];
        $data['csts'] = json_encode($csts);

        // return $data;

        return view('exams.schedule.index')->with($data);
    }



    public function datatable(Request $request)
    {


        try {


            $exam_schedules = ExamSchedule::leftJoin('academic_years', 'academic_years.id', 'exam_schedules.academic_year_id')
                ->leftJoin('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                ->leftJoin('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                ->leftJoin('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                ->select('exams.name as exam_name', 'semesters.name as semester_name', 'exam_schedules.uuid as myuuid', 'exam_schedules.id as ex_id', 'exam_schedules.start_from', 'exam_schedules.end_on', 'marking_from', 'marking_to', 'exam_schedules.status', 'academic_years.name as acdmc_year_name', 'grade_groups.name as grade_group_name')
                // ->orderBy('ex_id','desc')
                ->get();

            // Use sortByDesc to arrange the collection in descending order based on ex_id
            $exam_schedules = $exam_schedules->sortByDesc('ex_id')->values()->all();

            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($exam_schedules)

                ->editColumn('academic_year_id', function ($ex) {

                    return $ex->acdmc_year_name;
                })

                ->editColumn('exam_id', function ($ex) {

                    return $ex->exam_name;
                })

                ->editColumn('semester_id', function ($ex) {

                    return $ex->semester_name;
                })
                ->editColumn('grading', function ($ex) {

                    return $ex->grade_group_name;
                })

                ->editColumn('marking_from', function ($ex) {

                    return date('jS M Y', strtotime($ex->marking_from));
                })

                ->editColumn('start_from', function ($ex) {

                    return date('jS M Y', strtotime($ex->start_from));
                })

                ->editColumn('end_on', function ($ex) {

                    return date('jS M Y', strtotime($ex->end_on));
                })

                ->editColumn('marking_to', function ($ex) {

                    return date('jS M Y', strtotime($ex->marking_to));
                })

                ->editColumn('status', function ($ex) {

                    if ($ex->status == 'Upcoming') {

                        return '<span>  <i style="color:#069613; padding: 0.4rem 0.5rem;" class="fa-solid fa-right-to-bracket"></i> ' . $ex->status . ' </span>';
                    } elseif ($ex->status == 'Open') {
                        return '<span  class="badge badge-dim badge-success"> <i style="color:#069613" class="fa-solid fa-lock-open"></i>&nbsp;' . $ex->status . '</span>';
                    } else {

                        return '<span  class="badge badge-dim badge-danger"> <i style="color:#069613" class="fa-solid fa-lock"></i>&nbsp;' . $ex->status . '</span>';
                    }
                })

                ->editColumn('classes', function ($ex) {
                    $classes = '';
                    $variations = ExamScheduleClassStreamSubject::leftJoin('school_classes', 'school_classes.id', '=', 'exam_schedule_class_stream_subjects.class_id')
                        ->leftJoin('streams', 'streams.id', '=', 'exam_schedule_class_stream_subjects.stream_id')
                        ->leftJoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                        ->select('streams.name as stream_name', 'school_classes.name as class_name', 'subjects.name as subject_name')
                        ->where('exam_schedule_id', $ex->ex_id)
                        ->groupBy(['school_classes.id', 'streams.id'])->get();
                    foreach ($variations as $key => $vary) {
                        $class = $vary->class_name . ' ' . $vary->stream_name;
                        $classes .= '<button style="padding: 2px 5px;border-radius: 5px; margin-top:1rem;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> ' . $class . ' </button> &nbsp;';
                    }
                    return $classes;
                })

                ->editColumn('subjects', function ($ex) {

                    $subjects = '';
                    $variations = ExamScheduleClassStreamSubject::leftJoin('school_classes', 'school_classes.id', '=', 'exam_schedule_class_stream_subjects.class_id')
                        ->leftJoin('streams', 'streams.id', '=', 'exam_schedule_class_stream_subjects.stream_id')
                        ->leftJoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                        ->select('subjects.name as subject_name')
                        ->distinct() // Use the distinct method
                        ->where('exam_schedule_id', $ex->ex_id)
                        ->get();

                    foreach ($variations as $key => $vary) {
                        $subjects .= '<button style="padding: 2px 5px;border-radius: 5px; margin-top:1rem;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> ' . $vary->subject_name . ' </button> &nbsp;';
                    }

                    return $subjects;
                })

                ->editColumn('created_by', function ($ex) {

                    return User::find($ex->created_by);
                })

                ->addColumn('action', function ($ex) {
                    return '<span>
                    <button type="button" data-uuid="' . $ex->myuuid . '" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
                        | <button data-uuid="' . $ex->myuuid . '" type="button"   class="btn  btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
                    </span>';
                })

                ->rawColumns(['action', 'education_level_id', 'status', 'classes', 'subjects'])
                ->make();
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }


    public function store(Request $req)
    {

        try {

            // return $req->all();

            DB::beginTransaction();
            // return $req->class_streams;

            $status = '';
            // 13/10/2023

            $examStart = $this->global->toMysqlDateFormat($req->exam_start);
            // $examEnd = $this->global->toMysqlDateFormat($req->exam_end);
            $examEnd = $this->global->toMysqlDateFormat($req->end);
            $today = date('Y-m-d');

            if ($examStart < $today && $examEnd < $today) {
                $status = 'Closed';
            } elseif ($examStart > $today && $examEnd > $today) {
                $status = 'Upcoming';
            } elseif ($examStart <= $today && $examEnd >= $today) {
                $status = 'Open';
            }

            $today = date('Y-m-d');

            $exam_schedule = ExamSchedule::updateOrCreate(
                [
                    'uuid' => $req->uuid
                ],

                [
                    'name' => $req->name,
                    'uuid' => generateUuid(),
                    'grading' => $req->grade_group,
                    'start_from' => $this->global->toMysqlDateFormat($req->exam_start),
                    // 'end_on'=>$this->global->toMysqlDateFormat($req->exam_end),
                    'end_on' => $this->global->toMysqlDateFormat($req->end),
                    'semester_id' => $req->semester,
                    'status' => $status,
                    'academic_year_id' => $req->academic_year,
                    'marking_from' => $this->global->toMysqlDateFormat($req->mark_start),
                    'marking_to' => $this->global->toMysqlDateFormat($req->mark_end),
                    'created_by' => auth()->user()->id,
                    'exam_id' => $req->exam_type,
                ]

            );

            if ($exam_schedule) {

                $subjcts = $req->subjects;

                foreach (json_decode($req->class_streams) as $class_stream) {
                    $classId = $class_stream->class_id;
                    $streamId = $class_stream->stream_id;

                    foreach ($subjcts as $sbjct) {

                        ExamScheduleClassStreamSubject::create(
                            [
                                'class_id' => $classId,
                                'stream_id' => $streamId,
                                'exam_schedule_id' => $exam_schedule->id,
                                'subject_id' => $sbjct,
                                'uuid' => generateUuid(),
                                'created_by' => auth()->user()->id,
                            ]
                        );
                    }
                }
            }

            //    if ($exam_schedule) {

            //    $subjcts = $req->subjects;

            //    foreach ($subjcts as $key => $sbjct) {

            //     ExamScheduleSubject::create([

            //         'exam_schedule_id'=>$exam_schedule->id,
            //         'subject_id'=>$sbjct

            //     ]);

            //    }

            //    foreach ( json_decode($req->class_streams)  as $key => $class_stream) {

            //     ExamScheduleClassStreamSubject::create(
            //         [
            //             'class_id'=>$class_stream->class_id,
            //             'stream_id'=>$class_stream->stream_id ? $class_stream->stream_id : null ,
            //             'exam_schedule_id'=>$exam_schedule->id,
            //          ]

            //         );

            //    }


            //    }

            DB::commit();
            return response(['state' => 'done', 'msg' => 'success']);
        } catch (QueryException $e) {

            DB::rollback();
            return $e->getMessage();
        }
    }


    public function destroy(Request $req)
    {

        try {
            $uuid = $req->uuid;
            $class = ExamSchedule::where('uuid', $uuid)->first();
            $destroy = $class->delete();

            if ($destroy) {

                return response(['state' => 'done', 'msg' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function edit(Request $req)
    {

        // return $req;

        $exam_schedule = ExamSchedule::where('uuid', $req->uuid)->first();

        $ac_year = AcademicYear::where('id', $exam_schedule->academic_year_id)->first()->name;
        $schdle_semester = Semester::where('id', $exam_schedule->semester_id)->first()->name;
        $exam_type = Exam::where('id', $exam_schedule->exam_id)->first()->name;
        $s_date = $exam_schedule->start_from;
        $e_date = $exam_schedule->end_on;
        $mark_w_o = $exam_schedule->marking_from;
        $mark_w_c = $exam_schedule->marking_to;
        $grade_prfle = GradeGroup::where('id', $exam_schedule->grading)->first()->name;


        // fetching the subjects for a particular schedule



        $subjct_scheduled = ExamScheduleClassStreamSubject::select('*')
            ->leftJoin('school_classes', 'school_classes.id', '=', 'exam_schedule_class_stream_subjects.class_id')
            ->leftJoin('streams', 'streams.id', '=', 'exam_schedule_class_stream_subjects.stream_id')
            ->leftJoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
            ->where('exam_schedule_id', $exam_schedule->id)
            ->groupBy('subjects.id')
            ->get();

        $classes = '';
        $class_stream_scheduled = ExamScheduleClassStreamSubject::select('*')
            ->leftJoin('school_classes', 'school_classes.id', '=', 'exam_schedule_class_stream_subjects.class_id')
            ->leftJoin('streams', 'streams.id', '=', 'exam_schedule_class_stream_subjects.stream_id')
            ->leftJoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
            ->where('exam_schedule_id', $exam_schedule->id)
            ->groupBy('streams.id')
            ->get();

            foreach ($class_stream_scheduled as $key => $vary) {
                $class = $vary->class_name . ' ' . $vary->stream_name;
                $classes .= '<button style="padding: 2px 5px;border-radius: 5px; margin-top:1rem;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> ' . $class . ' </button> &nbsp;';
            }
            // return $classes;


        // return $class_stream_scheduled = ExamScheduleClassStreamSubject::leftJoin('school_classes', 'school_classes.id', '=', 'exam_schedule_class_stream_subjects.class_id')
        //     ->leftJoin('streams', 'streams.id', '=', 'exam_schedule_class_stream_subjects.stream_id')
        //     ->leftJoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
        //     ->select('exam_schedule_class_stream_subjects.class_id', 'exam_schedule_class_stream_subjects.stream_id', 'exam_schedule_class_stream_subjects.subject_id')
        //     ->where('exam_schedule_id', $exam_schedule->ex_id)
        //     ->groupBy(['class_id', 'stream_id']) // You may need to adjust the column names based on your actual database structure
        //     ->get();



         // Prepare data for JSON response
        $responseData = [
            'exam_schedule' => $exam_schedule,
            'ac_year' => $ac_year,
            'schdle_semester' => $schdle_semester,
            'exam_type' => $exam_type,
            's_date' => $s_date,
            'e_date' => $e_date,
            'mark_w_o' => $mark_w_o,
            'mark_w_c' => $mark_w_c,
            'grade_prfle' => $grade_prfle,
            'subjct_scheduled' => $subjct_scheduled,
            'classes' => $classes,
        ];

        // Return data as JSON response
        // return Response::json($responseData);

        return response($responseData);
    }
}
