<?php

namespace App\Http\Controllers\results;

use App\Exports\ResultsUploadTemplate;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamScheduleClassStreamSubject;
use App\Models\GradeGroup;
use App\Models\Result;
use App\Models\ResultDraft;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentResultReport;
use App\Models\Subject;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UploadController extends Controller
{
    //
    public $global;

    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }


    public function index($uuid, $sp, $grade_group_id, $sbjct_id, $class_id, $stream_id)
    {

        // return 'here';
        // return base64_decode($class_id);


        $data['exam_schedule'] = $exschecdule = ExamSchedule::where('uuid', $uuid)->first();

        $data['acdmc_year'] = AcademicYear::find($exschecdule->academic_year_id);
        $data['exam'] = Exam::find($exschecdule->exam_id);
        $data['gradingProfile'] = GradeGroup::find($exschecdule->grading);
        $data['semester'] = Semester::find($exschecdule->semester_id);
        $data['marking_to'] = $exschecdule->marking_to;
        $data['subject_model'] = Subject::find(base64_decode($sbjct_id));
        $data['class_model'] = SchoolClass::find(base64_decode($class_id));
        $data['stream_model'] = Stream::find(base64_decode($stream_id));
        $data['clasxs_id'] = $class_id;
        $data['stream_id'] = $stream_id;
        $data['subject_id'] = $sbjct_id;
        $data['grade_group_id'] = $grade_group_id;
        $data['sp'] = $sp;

        $data['activeTab'] = 'waitingForMarkingExamsTab';


        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'template';
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        return view('results.landing')->with($data);
    }

    public function system($uuid = null, $specific_uuid = null, $grade_group_uuid = null, $sbject_id = null, $class_id = null, $stream_id = null)
    {

        //  return $sbject_id;
        // return $class_id;
        //  return $stream_id;

        $data['exam_schedule'] = $exschecdule = ExamSchedule::where('uuid', $uuid)->first();

        $data['acdmc_year'] = AcademicYear::find($exschecdule->academic_year_id);
        $data['exam'] = Exam::find($exschecdule->exam_id);
        $data['gradingProfile'] = GradeGroup::find($exschecdule->grading);
        $data['semester'] = Semester::find($exschecdule->semester_id);
        $data['marking_to'] = $exschecdule->marking_to;
        $data['clasxs_id'] = $class_id;
        $data['stream_id'] = $stream_id;
        $data['subject_id'] = $sbject_id;
        $data['specific_uuid'] = $specific_uuid;
        $data['stream'] =  Stream::find(base64_decode($stream_id));
        $data['school_class'] = SchoolClass::find(base64_decode($class_id));
        $data['subject'] = Subject::find(base64_decode($sbject_id));
        $data['grade_group_id'] = $grade_group_uuid;


        $data['students'] = Student::all();
        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['activeTab'] = 'waitingForMarkingExamsTab';
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'system';
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        //  return $stream_id;

        return view('results.by_system')->with($data);
    }




    public function store(Request $req)
    {
        try {
            // return $req->all();
            DB::beginTransaction();

            $success = 0;
            $failed = 0;
            $result = '';

            $data['academic_year_id'] = $req->academic_year;
            $data['term_id'] = $req->semester;
            $data['class_id'] = base64_decode($req->class_id);
            $data['stream_id'] = base64_decode($req->stream_id);
            $data['subject_id'] = base64_decode($req->subject_id);
            $data['exam_id'] = $req->exam_type;

            $grade_group_id = GradeGroup::where('uuid', $req->gg)->first()->id;


            foreach ($req->marks as $key => $mark) {

                if ($mark) {
                    $result = Result::updateOrCreate(
                        [
                            'uuid' => $req->uuid,
                            'student_id' => $req->student_id[$key]
                        ],
                        [

                            'exam_id' => $req->exam_type,
                            'score' => $mark,
                            'full_name' => $req->full_name[$key],
                            'uuid' => generateUuid(),
                            'semester_id' => $req->semester,
                            'academic_year_id' => $req->academic_year,
                            'subject_id' => base64_decode($req->subject_id),
                            'class_id' => base64_decode($req->class_id),
                            'stream_id' => base64_decode($req->stream_id),
                            'grade_group_id' => $grade_group_id,
                            'created_by' => auth()->user()->id

                        ]
                    );

                    $success += 1;
                } else {

                    $failed += 1;
                }
            }

            DB::commit();



            // if ($result) {

            //     $schedule_id = ExamSchedule::where('uuid', $req->uuid)->first()->id;
            //     ExamScheduleClassStreamSubject::where(['uuid' => $req->sp, 'exam_schedule_id' => $schedule_id])->first()->delete();
            //     return response(['state' => 'done', 'success_count' => $success, 'data' => $data, 'failed_count' => $failed, 'type' => 'success', 'msg' => 'success']);
            // }
            if ($result) {
                $schedule_id = ExamSchedule::where('uuid', $req->uuid)->first()->id;
                $examScheduleClassStreamSubject = ExamScheduleClassStreamSubject::where(['uuid' => $req->sp, 'exam_schedule_id' => $schedule_id])->first();

                // Soft delete the record
                $examScheduleClassStreamSubject->delete();

                return response(['state' => 'done', 'success_count' => $success, 'data' => $data, 'failed_count' => $failed, 'type' => 'success', 'msg' => 'success']);
            }



            return response(['state' => 'fail', 'type' => 'error', 'failed_count' => $failed, 'msg' => 'Failed']);
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    // function to fetch update the list of students with no marks
    public function compareStudents(Request $request)
    {
        // return $request;
        // exit();

        $class_id = $request->class_id;
        $stream_id = $request->stream_id;
        $subject_id  = $request->subject_id;
        $exam_type = $request->exam_type;
        $semester_id = $request->semester;

        $mygrade_group = Exam::where('id', $exam_type)->first();

        // Get the list of students from the students table
        $students_og = Student::where('class_id', $class_id)
            ->where('stream_id', $stream_id)
            ->pluck('id');

        // Get the list of students from the students table for the specified subject
        $students = Student::where('class_id', $class_id)
            ->where('stream_id', $stream_id)
            ->whereHas('assignments', function ($query) use ($subject_id) {
                $query->where('subject_id', $subject_id);
            })
            ->pluck('id');

        // Get the list of students already in the results table
        $existing_students = Result::where('class_id', $class_id)
            ->where('stream_id', $stream_id)
            ->where('subject_id', $subject_id)
            ->where('exam_id', $exam_type)
            ->pluck('student_id');

        // Find students in the students table but not in the results table
        $new_students = $students->diff($existing_students);

        if ($new_students) {
            foreach ($new_students as $student_id) {
                $student = Student::find($student_id);
                if ($student) {
                    $fullname = trim("{$student->firstname} {$student->middlename} {$student->lastname}");

                    Result::create([
                        'student_id' => $student_id,
                        'class_id' => $class_id,
                        'stream_id' => $stream_id,
                        'score' => 0,
                        'exam_id' => $request->exam_type,
                        'full_name' => $fullname,
                        'uuid' => generateUuid(),
                        'semester_id' => $request->semester,
                        'academic_year_id' => $request->acdmcyear,
                        'subject_id' => $request->subject_id,
                        'grade_group_id' => $mygrade_group->grade_group,
                        'created_by' => auth()->user()->id

                    ]);
                }
            }

            return response(['state' => 'done', 'type' => 'success', 'msg' => 'success fetch new students.']);
        } else {
            return response(['state' => 'fail', 'type' => 'error', 'msg' => 'Failed to fetch new students.']);
        }
    }


    public function destroy(Request $req)
    {
        try {
            $uuid = $req->uuid;
            $result = Result::where('uuid', $uuid)->first();
            $destroy = $result->delete();

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

        $result  = Result::where('uuid', $req->uuid)->first();
        return response($result);
    }



    public function templateQuery_og(Request $req)
    {
        // return $req;
        $data['uuid'] = $req->uuid;
        $data['sp'] = $req->sp;
        $data['gg'] = $req->grade_group_id;
        $data['class_id'] = $req->class_id;
        $data['stream_id'] = $req->stream_id;
        $data['exam_type'] = $req->exam_type;
        $data['subject_type'] = $req->subject_id;
        $data['acdmcyear'] = $req->acdmcyear;
        $data['semester'] = $req->semester;



        // $students = Student::orWhere('class_id', base64_decode($req->class_id));
        $students = Student::where('class_id', base64_decode($req->class_id));


        if ($req->stream_id) {
            $students->where('stream_id', base64_decode($req->stream_id));
        }


        $data['students'] = $students;
        $data['examInfo'] = Exam::find($req->exam_type);

        $data['students_count'] = $students_count =  count($students->get());

        $view = view('results.by_system_part', $data)->render();
        $data['base_html'] = $view;

        return  response($data);
    }

    // mine fetch student subject based
    public function templateQuery(Request $req)
    {
        $data['uuid'] = $req->uuid;
        $data['sp'] = $req->sp;
        $data['gg'] = $req->grade_group_id;
        $data['class_id'] = $req->class_id;
        $data['stream_id'] = $req->stream_id;
        $data['exam_type'] = $req->exam_type;
        $data['subject_type'] = $req->subject_id;
        $data['acdmcyear'] = $req->acdmcyear;
        $data['semester'] = $req->semester;

        // Retrieve students studying a particular subject in the stream
        $students_og = Student::whereHas('assignments', function ($query) use ($req) {
            $query->where('subject_id', base64_decode($req->subject_id))
                ->where('class_id', base64_decode($req->class_id))
                ->where('stream_id', base64_decode($req->stream_id));
        });

        $students = Student::whereHas('assignments', function ($query) use ($req) {
            $query->where('subject_id', base64_decode($req->subject_id))
                  ->where('class_id', base64_decode($req->class_id))
                  ->where('stream_id', base64_decode($req->stream_id));
        })
        ->orderBy('gender', 'desc')
        ->orderBy('firstname', 'asc');



        $data['students'] = $students->get();
        $data['examInfo'] = Exam::find($req->exam_type);

        $data['students_count'] = count($data['students']);

        $view = view('results.by_system_part', $data)->render();
        $data['base_html'] = $view;

        return response($data);
    }


    public function streams(Request $req)
    {
        $streams =  Stream::where('class_id', $req->id)->get();

        $elevelid = SchoolClass::find($req->id)->education_level_id;
        $subjects = Subject::join('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')
            ->join('education_levels', 'education_levels.id', '=', 'subject_education_levels.education_level_id')
            ->select('subjects.name as sbjct_name', 'subjects.id as sbjct_id')
            ->where('education_level_id', $elevelid)
            ->get();

        $subjects_html = '<option> </option>';
        foreach ($subjects as $key => $subject) {
            $subjects_html .= '<option value="' . $subject->sbjct_id . '"> ' . $subject->sbjct_name . ' </option>';
        }



        $streams_html = '<option> </option>';

        foreach ($streams as $key => $stream) {

            $streams_html .= '<option value="' . $stream->id . '"> ' . $stream->name . ' </option>';
        }

        $data['streams'] = $streams_html;
        $data['subjects'] = $subjects_html;

        return response($data);
    }


    public function exportTemplate_og(Request $req)
    {
        //  return $req->all();

        $streamname = '';

        $students = Student::where('class_id', base64_decode($req->class_id));

        $classname = SchoolClass::find(base64_decode($req->class_id))->name;
        if ($req->stream_id) {

            $streamname = Stream::find(base64_decode($req->stream_id))->name;
            $students->where('stream_id', base64_decode($req->stream_id));
        }

        $students = $students->get();
        $sbjct_name = Subject::find(base64_decode($req->subject_id))->name;
        $filename = $classname . ' ' . $streamname . ' ' . $sbjct_name . ' ' . 'template';

        return Excel::download(new ResultsUploadTemplate($students), '' . $filename . '.xlsx');
    }

    public function exportTemplate(Request $req)
    {
        $classId = base64_decode($req->class_id);
        $streamId = base64_decode($req->stream_id);
        $subjectId = base64_decode($req->subject_id);

        $students = Student::where('class_id', $classId);

        $className = SchoolClass::find($classId)->name;
        $streamName = '';
        if ($streamId) {
            $streamName = Stream::find($streamId)->name;
            $students->where('stream_id', $streamId);
        }

        // Filter students by subject ID
        $students->whereHas('assignments', function ($query) use ($subjectId, $classId, $streamId) {
            $query->where('subject_id', $subjectId)
                ->where('class_id', $classId);
            if ($streamId) {
                $query->where('stream_id', $streamId);
            }
        });

        $students = $students->get();
        $subjectName = Subject::find($subjectId)->name;
        $filename = $className . ' ' . $streamName . ' ' . $subjectName . ' ' . 'template';

        return Excel::download(new ResultsUploadTemplate($students), $filename . '.xlsx');
    }


    /* DRAFTS */

    public function updateDraftsMark(Request $req)
    {

        try {

            $draftUpdate =  ResultDraft::where('uuid', $req->uuid)->first()->update(['score' => $req->score]);

            if ($draftUpdate) {

                $data = ['state' => 'done', 'msg' => 'success', 'title' => 'success'];
                return response($data);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function Draftstore_og(Request $req)
    {


        try {

            // return $req->all();

            DB::beginTransaction();

            $success = 0;
            $failed = 0;
            $result = '';



            $data['academic_year_id'] = $req->academic_year;
            $data['term_id'] = $req->semester;
            $data['class_id'] = $req->class_id;
            $data['stream_id'] = $req->stream_id;
            $data['subject_id'] = $req->subject_id;
            $data['exam_id'] = $req->exam_type;

            foreach ($req->marks as $key => $mark) {

                if ($mark) {
                    $result = ResultDraft::updateOrCreate(
                        [
                            'uuid' => $req->uuid
                        ],
                        [

                            'student_id' => $req->student_id[$key],
                            'exam_id' => $req->exam_type,
                            'score' => $mark,
                            'full_name' => $req->full_name[$key],
                            'uuid' => generateUuid(),
                            'semester_id' => $req->semester,
                            'academic_year_id' => $req->academic_year,
                            'subject_id' => $req->subject_id,
                            'class_id' => $req->class_id,
                            'stream_id' => $req->stream_id


                        ]
                    );

                    $success += 1;
                } else {

                    $failed += 1;
                }
            }

            DB::commit();

            if ($result) {

                return response(['state' => 'done', 'success_count' => $success, 'data' => $data, 'failed_count' => $failed, 'type' => 'success', 'msg' => 'success']);
            }

            return response(['state' => 'fail', 'type' => 'error', 'failed_count' => $failed, 'msg' => 'Failed']);
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function Draftstore(Request $req)
    {
        try {
            // return $req;
            DB::beginTransaction();

            $success = 0;
            $failed = 0;
            $data = [];


            $data['academic_year_id'] = $req->academic_year;
            $data['term_id'] = $req->semester;
            $data['class_id'] = base64_decode($req->class_id);
            $data['stream_id'] = base64_decode($req->stream_id);
            $data['subject_id'] = base64_decode($req->subject_id);
            $data['exam_id'] = $req->exam_type;

            foreach ($req->marks as $key => $mark) {
                if ($mark !== null) {
                    $result = ResultDraft::updateOrCreate(
                        ['uuid' => $req->uuid],
                        [
                            'student_id' => $req->student_id[$key],
                            'exam_id' => $req->exam_type,
                            'score' => $mark,
                            'full_name' => $req->full_name[$key],
                            'uuid' => generateUuid(),
                            'semester_id' => $req->semester,
                            'academic_year_id' => $req->academic_year,
                            'subject_id' => base64_decode($req->subject_id),
                            'class_id' => base64_decode($req->class_id),
                            'stream_id' => base64_decode($req->stream_id)
                        ]
                    );

                    if ($result) {
                        $success++;
                    } else {
                        $failed++;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'state' => 'done',
                'success_count' => $success,
                'failed_count' => $failed,
                'msg' => 'success',
                'data' => $data
            ]);
        } catch (QueryException $e) {
            DB::rollback(); // Rollback transaction on failure
            return response()->json([
                'state' => 'fail',
                'msg' => $e->getMessage()
            ]);
        }
    }




    public function Draftdatatable(Request $request)
    {


        try {


            //     'student_id',
            // 'exam_id',
            // 'score',
            // 'full_name',
            // 'semester_id',
            // 'academic_year_id',
            // 'subject_id',
            // 'class_id',
            // 'stream_id',
            // 'uuid'




            $drafts = ResultDraft::join('academic_years', 'result_drafts.academic_year_id', '=', 'academic_years.id')
                ->join('semesters', 'result_drafts.semester_id', '=', 'semesters.id')
                ->join('school_classes', 'school_classes.id', '=', 'result_drafts.class_id')
                ->join('subjects', 'result_drafts.subject_id', '=', 'subjects.id')
                ->join('streams', 'streams.id', '=', 'result_drafts.stream_id')
                ->join('exams', 'exams.id', '=', 'result_drafts.exam_id')
                ->select('academic_years.name as acnm', 'result_drafts.uuid as result_uuid', 'result_drafts.semester_id', 'result_drafts.class_id', 'result_drafts.subject_id', 'result_drafts.stream_id', 'result_drafts.academic_year_id', 'result_drafts.exam_id', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.name as semester_name')
                ->groupBy(['result_drafts.academic_year_id', 'result_drafts.semester_id', 'result_drafts.class_id', 'result_drafts.exam_id', 'result_drafts.subject_id', 'result_drafts.stream_id'])
                ->get();

            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($drafts)


                ->editColumn('class_id', function ($draft) {


                    return $draft->class_name;
                })

                ->editColumn('stream_id', function ($draft) {

                    return $draft->stream_name;
                })
                ->editColumn('exam_id', function ($draft) {


                    return $draft->exam_name;
                })
                ->editColumn('subject_id', function ($draft) {

                    return $draft->sbjctname;
                })
                ->editColumn('semester_id', function ($draft) {

                    return $draft->semester_name;
                })



                ->editColumn('academic_year_id', function ($draft) {

                    return $draft->acnm;
                })

                ->addColumn('action', function ($draft) {
                    return '<span>
             <a style="color:white" type="button" href="' . route("results.sytem.drafts.editable.index", ['year_id' => $this->global->base64url_encode($draft->academic_year_id), 'semester_id' => $this->global->base64url_encode($draft->semester_id), 'class_id' => $this->global->base64url_encode($draft->class_id), 'stream_id' => $this->global->base64url_encode($draft->stream_id), 'exam_id' => $this->global->base64url_encode($draft->exam_id), 'subject_id' => $this->global->base64url_encode($draft->subject_id)]) . '" data-semester_id="' . $draft->semester_id . '" data-class_id="' . $draft->class_id . '" data-exam_id="' . $draft->exam_id . '" data-academic_year_id="' . $draft->academic_year_id . '" data-subject_id="' . $draft->subject_id . '" data-stream_id="' . $draft->stream_id . '"  class="btn btn-custon-four text-white view-incomplete btn-info btn-xs"><i class="fa fa-eye"></i></a>
             | <button title="submit" type="button"  data-uuid="' . $draft->uuid . '" class="btn btn-custon-four btn-success btn-xs submit"><i class="fa-solid fa-upload"></i></button>
             | <button title="delete" data-uuid="' . $draft->uuid . '" type="button" class="btn btn-custon-four btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    //  public function store(Request $req){

    //     try {

    //         $classes = SchoolClass::updateOrCreate(
    //             [

    //                 'uuid' =>$req->uuid

    //             ],

    //             [

    //             'name'=>$req->name,
    //             'education_level_id'=>$req->education_level_id,
    //             'uuid'=>generateUuid(),
    //             'capacity'=>$req->capacity,
    //             'created_by'=>1

    //            ]);

    //            if ($classes) {

    //             return response(['state'=>'done', 'msg'=> 'success']);


    //            }


    //     } catch (QueryException $e) {

    //         return $e->getMessage();

    //     }
    // }


    // public function destroy(Request $req){

    //     try {
    //     $uuid = $req->uuid;
    //     $class = SchoolClass::where('uuid',$uuid)->first();
    //     $destroy = $class->delete();

    //     if ($destroy) {

    //         return response(['state'=>'done', 'msg'=>'success']);
    //         # code...
    //     }

    //     } catch (QueryException $e) {

    //         return $e->getMessage();
    //     }

    // }


    // public function edit(Request $req){


    //     $classes  = SchoolClass::where('uuid',$req->uuid)->first();
    //     return response($classes);



    //   }








}
