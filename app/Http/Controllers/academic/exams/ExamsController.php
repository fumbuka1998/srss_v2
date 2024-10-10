<?php

namespace App\Http\Controllers\academic\exams;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamSchedule;
use App\Models\ExamScheduleClassStreamSubject;
use App\Models\ExamSubject;
use App\Models\GradeGroup;
use App\Models\Module;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\User;
use Carbon\Carbon;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;


class ExamsController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }
    public function index()
    {

        $data['grade_profiles'] = GradeGroup::all();
        $data['subjects'] = Subject::all();
        $data['classes'] = SchoolClass::all();
        return view('configurations.academic.exams.exams')->with($data);
    }

    public function assignPreliminaries()
    {

        $data['subjects'] = Subject::all();
        $data['classes'] = SchoolClass::all();
        return response($data);
    }



    public function datatable(Request $request)
    {


        try {

            $exams = Exam::all();
            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($exams)

                ->editColumn('created_by', function ($exam) {

                    $user = User::find($exam->created_by);
                    return $user ? $user->full_name : 'admin';
                })

                ->addColumn('action', function ($exam) {
                    return '<span>

              <button type="button" data-uuid="' . $exam->uuid . '" class="btn btn-custon-four btn-info btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button  data-uuid="' . $exam->uuid . '" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
            </span>';
                })

                ->rawColumns(['action'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }




    public function store_og(Request $req)
    {

        try {

            // return $req->all();

            // return $req->isCummulative;
            DB::beginTransaction();



            $exam = Exam::updateOrCreate(
                [
                    'uuid' => $req->uuid
                ],

                [
                    'name' => $req->name,
                    'code' => $req->code,
                    'total_marks' => $req->total_marks,
                    'passmark' => $req->passmark,
                    'uuid' => generateUuid(),
                    'isCommutative' => $req->isCummulative,
                    'is_dp' => $req->isdP,
                    'grade_group' => $req->grade_group,
                    'created_by' => auth()->user()->id

                ]
            );


            foreach ($req->classes as $key => $class) {

                ExamClass::create(
                    [
                        'exam_id' => $exam->id,
                        'class_id' => $class
                    ]

                );
            }


            foreach ($req->subjects as $key => $subject) {

                ExamSubject::create(
                    [
                        'subject_id' => $subject,
                        'exam_id' => $exam->id
                    ]
                );
            }


            DB::commit();

            if ($exam) {

                return response(['state' => 'done', 'msg' => 'Exam Creation Success', 'title' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function store(Request $req)
    {

        try {

            // return $req->all();

            // return $req->isCummulative;
            DB::beginTransaction();



            $exam = Exam::updateOrCreate(
                [
                    'uuid' => $req->uuid
                ],

                [
                    'name' => $req->name,
                    'code' => $req->code,
                    'total_marks' => $req->total_marks,
                    'passmark' => $req->passmark,
                    'uuid' => generateUuid(),
                    'isCommutative' => $req->isCummulative,
                    'is_dp' => $req->isdP,
                    'grade_group' => $req->grade_group,
                    'created_by' => auth()->user()->id

                ]
            );


            //    foreach ($req->classes as $key => $class) {

            //     ExamClass::create(
            //     [
            //      'exam_id'=> $exam->id,
            //      'class_id'=>$class
            //     ]

            //  );
            //  }


            //  foreach ($req->subjects as $key => $subject) {

            //     ExamSubject::create(
            //         [
            //             'subject_id'=>$subject,
            //             'exam_id'=>$exam->id
            //         ]
            //         );


            //     }


            DB::commit();

            if ($exam) {

                return response(['state' => 'done', 'msg' => 'Exam Creation Success', 'title' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function destroy(Request $req)
    {
        try {
            $uuid = $req->uuid;
            $class = Exam::where('uuid', $uuid)->first();
            $destroy = $class->delete();

            if ($destroy) {

                return response(['state' => 'done', 'msg' => ' deletion successfully', 'title' => 'success']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function edit(Request $req)
    {

        $exam  = Exam::where('uuid', $req->uuid)->first();
        return response($exam);
    }







    /* GRANO TODAY CHANGES 14TH OCTOBER 2023 */




    public function waitingForMarkingIndex()
    {
        $data['activeTab'] = 'waitingForMarkingExamsTab';
        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'template';
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        // return $data;

        return  view('exams.waitingformarking.index')->with($data);
    }

    public function waitingForMarkingDatatable(Request $request)
    {


        try {

            // return 'this is the datatable';$data['isAdmin'] = $isAdmin; 
            $todays_date = date('Y-m-d');
            $user = auth()->user();
            $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();
            $data['isAdmin'] = $isAdmin = $user->hasRole('Admin'); 
            if ($user->hasRole('Admin')) {

                $ids = ExamScheduleClassStreamSubject::pluck('id');

                $exam_schedules =   ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                    ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                    ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                    ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                    ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                    ->join('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                    ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                    ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                    ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                    ->select(
                        'academic_years.name as acdmc_name',
                        'exam_schedules.id as ex_id',
                        'semesters.name as semi_name',
                        'grade_groups.name as grade_name',
                        'grade_groups.uuid as grade_group_uuid',
                        'exams.name as exam_name',
                        'exam_schedules.uuid as exschedule_uuid',
                        'users.firstname',
                        'users.lastname',
                        'streams.name as stream_name',
                        'streams.id as stream_id',
                        'marking_to',
                        'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                        'school_classes.name as class_name',
                        'school_classes.id as sclass_id',
                        'subjects.id as sbjct_id',
                        'subjects.name as sbjct_name'
                    )
                    ->where('marking_from', '<=', $todays_date)
                    ->whereIn('exam_schedule_class_stream_subjects.uuid', $ids)
                    ->whereNull('exam_schedule_class_stream_subjects.deleted_at')
                    ->orderBy('ex_id', 'desc')
                    ->get();

                // $streamIds = collect($exam_schedules)->pluck('stream_id')->unique()->values()->all();

                // return $exam_schedules;

            } else {


                if (count($assignments)) {

                    $exam_schedules = ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                        ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                        ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                        ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                        ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                        ->join('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                        ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                        ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                        ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                        ->select(
                            'academic_years.name as acdmc_name',
                            'exam_schedules.id as ex_id',
                            'semesters.name as semi_name',
                            'grade_groups.name as grade_name',
                            'grade_groups.uuid as grade_group_uuid',
                            'exams.name as exam_name',
                            'exam_schedules.uuid as exschedule_uuid',
                            'users.firstname',
                            'users.lastname',
                            'streams.name as stream_name',
                            'streams.id as stream_id',
                            'marking_to',
                            'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                            'school_classes.name as class_name',
                            'school_classes.id as sclass_id',
                            'subjects.id as sbjct_id',
                            'subjects.name as sbjct_name'
                        )
                        ->where(function ($query) use ($assignments) {
                            foreach ($assignments as $assignment) {
                                $query->orWhere(function ($innerQuery) use ($assignment) {
                                    $innerQuery->where('exam_schedule_class_stream_subjects.class_id', $assignment->class_id)
                                        ->where('exam_schedule_class_stream_subjects.stream_id', $assignment->stream_id)
                                        ->where('exam_schedule_class_stream_subjects.subject_id', $assignment->subject_id)
                                        ->whereNull('exam_schedule_class_stream_subjects.deleted_at');
                                });
                            }
                        })
                        ->where('marking_from', '<=', $todays_date)
                        ->orderBy('ex_id', 'desc')
                        ->get();
                } else {

                    $exam_schedules = array();
                }
            }



            $search = $request->get('search');

            if (!empty($search)) {
            }

            return DataTables::of($exam_schedules)

                ->editColumn('academic_year_id', function ($ex) {

                    return $ex->acdmc_name;
                })

                ->editColumn('exam_id', function ($ex) {

                    return $ex->exam_name;
                })

                ->addColumn('marking_ends', function ($ex) {

                    return '<span data-row-id="' . $ex->exschedule_uuid . '"  class="regular_timer" data-marking-end="' . $ex->marking_to . '"> <i style="color:#008080" class="fa-regular fa-clock fa-spin"></i> <span id="' . $ex->uuid . '" class="clock_time">  </span> </span>';
                })

                ->addColumn('stream_name', function ($ex) {

                    return $ex->stream_name;
                })

                ->editColumn('semester_id', function ($ex) {

                    return $ex->semi_name;
                })
                ->editColumn('grading', function ($ex) {

                    return $ex->grade_name;
                })

                ->editColumn('classes', function ($ex) {

                    return $ex->class_name;
                })

                ->editColumn('subject_id', function ($ex) {

                    return $ex->sbjct_name;
                })

                ->editColumn('created_by', function ($ex) {

                    return $ex->firstname . ' ' . $ex->lastname;
                })

                ->addColumn('action', function ($ex) use ($isAdmin) {

                    $stream_id = $ex->stream_id ? $ex->stream_id : null;
                    $cursorStyle = $isAdmin ? 'cursor: pointer;' : '';
                    $markingFrom = Carbon::parse($ex->marking_from);
                    $markingTo = Carbon::parse($ex->marking_to);
                
                    // Check if marking_to is less than marking_from
                    if ($markingFrom->lessThan($markingTo)) {
                        return '<span>

                        <a  href="' . route('results.sytem.entry.index', ['uuid' => $ex->exschedule_uuid, 'specific_uuid' => $ex->blogic_uuid, 'grade_group_id' => $ex->grade_group_uuid, 'sbjct_id' => base64_encode($ex->sbjct_id), 'class_id' => base64_encode($ex->sclass_id), 'stream_id' => base64_encode($ex->stream_id)]) . '" data-uuid="' . $ex->exschedule_uuid . '" class="enter_marks btn btn-outline-primary btn-sm"><i class="fas fa-pencil-alt"></i>
                            </i>
                        </a><span>';
                    }

                    return '<span>

                    <a  href="' . route('results.sytem.entry.index', ['uuid' => $ex->exschedule_uuid, 'specific_uuid' => $ex->blogic_uuid, 'grade_group_id' => $ex->grade_group_uuid, 'sbjct_id' => base64_encode($ex->sbjct_id), 'class_id' => base64_encode($ex->sclass_id), 'stream_id' => base64_encode($ex->stream_id)]) . '" data-uuid="' . $ex->exschedule_uuid . '" class="enter_marks btn btn-outline-primary btn-sm"><i class="fas fa-pencil-alt"></i>
                        </i>
                    </a>
                    <a type="button" class="locked ' . ($isAdmin ? '' : 'd-none') . '"  style="' . $cursorStyle . '" data-exam-id=" '.$ex->ex_id.' ">
                        <i class="fa-solid fa-lock" style="color:#069613; background: rgba(217, 83, 79, 0.1);"></i> Locked
                    </a>

                    </span> &nbsp;';
                })

                ->rawColumns(['action', 'marking_ends'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    //search datatable
    //    $exam_schedules = $exam_schedules->where('account_student_details.first_name', 'like', '%'.$search.'%')
    //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
    //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
    //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
    //    }

    // $exam_schedules = $invoices->groupBy('invoices.id');


    public function extendMarkingTime(Request $request)
    {
        
        $examId = $request->exam_id;
        $exam = ExamSchedule::findOrFail($examId);

        // Extend marking time by adding 3 days to the current marking end date
        $newMarkingEnd = Carbon::parse($exam->marking_ends)->addDays(3);
        $exam->update(['marking_to' => $newMarkingEnd]);

        return response()->json(['msg' => 'Marking time extended by 3 days successfully','title'=>'success'], 200);
    }


    public function UpComingExamsIndex()
    {

        $data['activeTab'] = 'upcomingExamsTab';
        $data['exam_subjects'] = ExamSubject::all();
        $data['grade_profiles'] = GradeGroup::all();
        $data['subjects'] = Subject::all();
        $data['semesters'] = Semester::all();
        $data['exam_types'] = Exam::all();
        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = SchoolClass::all();
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();
        return  view('exams.upcoming.index')->with($data);
    }



    public function upComingDatatable(Request $request)
    {
        try {

            $todays_date = date('Y-m-d');
            $exam_schedules = ExamSchedule::where('start_from', '>', $todays_date)->where('end_on', '>', $todays_date);
            $user = auth()->user();
            $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();
            $ids = ExamScheduleClassStreamSubject::pluck('id');

            if ($user->getRoleNames()[0] == 'Admin') {

                $exam_schedules = ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                    ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                    ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                    ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                    ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                    ->leftjoin('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                    ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                    ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                    ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                    ->select(
                        'academic_years.name as acdmc_name',
                        'semesters.name as semi_name',
                        'grade_groups.name as grade_name',
                        'exams.name as exam_name',
                        'exam_schedules.uuid as exschedule_uuid',
                        'users.firstname',
                        'users.lastname',
                        'streams.name as stream_name',
                        'streams.id as stream_id',
                        'marking_to',
                        'exam_schedules.id as x_id',
                        'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                        'school_classes.name as class_name',
                        'school_classes.id as sclass_id',
                        'subjects.id as sbjct_id',
                        'subjects.name as sbjct_name'
                    )
                    ->where('start_from', '>', $todays_date)->where('end_on', '>', $todays_date)
                    ->whereIn('exam_schedule_class_stream_subjects.uuid', $ids)
                    ->orderBy('x_id', 'desc')
                    ->get();
            } else {

                if (count($assignments)) {

                    $exam_schedules = ExamSchedule::join('academic_years', 'academic_years.id', '=', 'exam_schedules.academic_year_id')
                        ->join('semesters', 'semesters.id', '=', 'exam_schedules.semester_id')
                        ->join('grade_groups', 'grade_groups.id', '=', 'exam_schedules.grading')
                        ->join('exams', 'exams.id', '=', 'exam_schedules.exam_id')
                        ->leftjoin('users', 'users.id', '=', 'exam_schedules.created_by')
                        ->leftjoin('exam_schedule_class_stream_subjects', 'exam_schedule_class_stream_subjects.exam_schedule_id', '=', 'exam_schedules.id')
                        ->leftjoin('school_classes', 'exam_schedule_class_stream_subjects.class_id', '=', 'school_classes.id')
                        ->leftjoin('streams', 'exam_schedule_class_stream_subjects.stream_id', '=', 'streams.id')
                        ->leftjoin('subjects', 'exam_schedule_class_stream_subjects.subject_id', '=', 'subjects.id')
                        ->select(
                            'academic_years.name as acdmc_name',
                            'semesters.name as semi_name',
                            'grade_groups.name as grade_name',
                            'exams.name as exam_name',
                            'exam_schedules.uuid as exschedule_uuid',
                            'users.firstname',
                            'users.lastname',
                            'streams.name as stream_name',
                            'streams.id as stream_id',
                            'marking_to',
                            'exam_schedules.id as x_id',
                            'exam_schedule_class_stream_subjects.uuid as blogic_uuid',
                            'school_classes.name as class_name',
                            'school_classes.id as sclass_id',
                            'subjects.id as sbjct_id',
                            'subjects.name as sbjct_name'
                        )
                        ->where('start_from', '>', $todays_date)->where('end_on', '>', $todays_date)
                        ->where(function ($query) use ($assignments) {
                            foreach ($assignments as $assignment) {
                                $query->orWhere(function ($innerQuery) use ($assignment) {
                                    $innerQuery->where('exam_schedule_class_stream_subjects.class_id', $assignment->class_id)
                                        ->where('exam_schedule_class_stream_subjects.stream_id', $assignment->stream_id)
                                        ->where('exam_schedule_class_stream_subjects.subject_id', $assignment->subject_id);
                                });
                            }
                        })
                        ->orderBy('x_id', 'desc')
                        ->get();
                } else {
                    // return 'no data';

                    $exam_schedules = array();
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

            return DataTables::of($exam_schedules)

                ->editColumn('academic_year_id', function ($ex) {

                    return $ex->acdmc_name;

                    // return '<a href="'.route('students.profile',$invoice->student_name->student_id).'" > '.$invoice->student_name->first_name .' '. $invoice->student_name->last_name.'</a>';
                })
                ->editColumn('semester_id', function ($ex) {

                    return $ex->semi_name;
                })
                ->editColumn('classes', function ($ex) {

                    return $ex->class_name;
                })
                ->editColumn('stream_name', function ($ex) {

                    return $ex->stream_name;
                })

                ->editColumn('exam_id', function ($ex) {

                    return $ex->exam_name;
                })

                ->editColumn('subject_id', function ($ex) {

                    return $ex->sbjct_name;
                })

                ->editColumn('marking_ends', function ($ex) {

                    return '<span data-row-id="' . $ex->exschedule_uuid . '"  class="regular_timer" data-marking-end="' . $ex->marking_to . '"> <i style="color:#008080" class="fa-regular fa-clock fa-spin"></i> <span id="' . $ex->uuid . '" class="clock_time">  </span> </span>';
                })

                //  <button disabled type="button" class="btn btn-custon-four btn-info btn-sm"><i class="fa fa-eye"></i></button>
                ->addColumn('action', function ($ex) {
                    return '<span>
           
              <button disabled type="button" data-uuid="' . $ex->uuid . '" class="btn btn-custon-four btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
             | <button disabled data-uuid="' . $ex->uuid . '" type="button" class="btn btn-custon-four btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'marking_ends'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function getCountdownTime(Request $request)
    {
        $endTimeString = $request->input('marking_end');
        $currentTime = Carbon::now();
        $endTime = Carbon::parse($endTimeString);
        $timeDiff = $endTime->diff($currentTime);

        $days = $timeDiff->days;
        $hours = $timeDiff->h;
        $minutes = $timeDiff->i;
        $seconds = $timeDiff->s;

        $timeDifference = [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ];

        return response($timeDifference);
    }
}
