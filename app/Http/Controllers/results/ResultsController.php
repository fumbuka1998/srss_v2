<?php

namespace App\Http\Controllers\results;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\GradeGroup;
use App\Models\Result;
use App\Models\ResultDraft;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\User;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf as PDF;



class ResultsController extends Controller
{
    public $global;

    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }


    public function draftsEditableEdit(Request $req)
    {

        $result =  ResultDraft::where('uuid', $req->uuid)->first();

        return $result;
    }


    public function draftsEditableDatatable(Request $request)
    {
        try {

            // return $request->all();

            $academic_year = $request->acdmcyear;
            $class_id = $request->class_id;
            $stream_id = $request->stream_id;
            $exam_type = $request->exam_type;
            $subject_id = $request->subject_id;
            $semester_id = $request->semester;
            $elevel = SchoolClass::find($class_id)->educationLevels->id;

            $examInfo = Exam::find($exam_type);

            $data['subject'] = Subject::find($subject_id);

            $results = Student::join('result_drafts', 'students.id', 'result_drafts.student_id')
                ->join('subjects', 'result_drafts.subject_id', '=', 'subjects.id')
                ->select('result_drafts.score', 'result_drafts.uuid', 'result_drafts.uuid', 'result_drafts.student_id', 'students.firstname', 'students.middlename', 'students.lastname')
                ->where('result_drafts.class_id', $class_id)
                ->where('result_drafts.semester_id', $semester_id)
                ->where('result_drafts.academic_year_id', $academic_year);

            if ($exam_type) {
                $results->where('result_drafts.exam_id', $exam_type);
            }

            if ($stream_id) {
                $results->where('result_drafts.stream_id', $stream_id);
                $data['stream'] = Stream::find($stream_id)->name;
            }

            if ($subject_id) {
                $results->where('result_drafts.subject_id', $subject_id);
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

            return DataTables::of($results)


                ->editColumn('class_id', function ($inc) {


                    return $inc->class_name;
                })

                ->addColumn('sn', function ($inc) {

                    return '';
                })

                ->addColumn('admission_no', function ($inc) {

                    return $inc->student_id;
                })

                ->addColumn('full_name', function ($inc) {
                    return $inc->firstname . ' ' . $inc->middlename . ' ' . $inc->lastname;
                })

                ->addColumn('percentage', function ($inc) use ($examInfo) {

                    return $this->global->marksToPercentage($inc->score, $examInfo->total_marks);
                })
                ->addColumn('remarks', function ($inc) use ($examInfo, $elevel) {

                    if ($inc->score !== '-') {

                        $grade_collective = $this->global->getGrade($inc->score, $elevel, $examInfo);
                        return $grade_collective['remarks'];
                    } else {
                        return '-';
                    }
                })

                ->addColumn('grade', function ($inc) use ($examInfo, $elevel) {

                    if ($inc->score !== '-') {

                        $grade_collective = $this->global->getGrade($inc->score, $elevel, $examInfo);
                        return $grade_collective['grade'];
                    } else {
                        return '-';
                    }
                })

                //  ->addColumn('grade',function($student) use($examInfo,$elevel,$students_result){
                //     $score = $students_result[$student->id]['score'] ?? '-';

                //     if ($score !== '-') {
                //         $percentage = $this->global->marksToPercentage($score, $examInfo->total_marks);
                //         $grade_collective = $this->global->getGrade($percentage,$elevel);
                //         return $grade_collective['grade'];

                //     } else {
                //         return '-';
                //     }
                // })

                ->addColumn('score', function ($inc) {

                    return $inc->score;
                })

                ->editColumn('stream_id', function ($inc) {

                    return $inc->stream_name;
                })
                ->editColumn('exam_id', function ($inc) {


                    return $inc->exam_name;
                })
                ->editColumn('subject_id', function ($inc) {

                    return $inc->sbjctname;
                })
                ->editColumn('semester_id', function ($inc) {

                    return $inc->semester_name;
                })

                ->editColumn('academic_year_id', function ($inc) {

                    return $inc->acnm;
                })

                ->addColumn('action', function ($inc) {
                    return '<span>
             <button type="button" data-uuid="' . $inc->uuid . '" class="btn btn-custon-four btn-primary btn-xs edit"><i class="fa fa-edit"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function index()
    {

        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'template';
        $data['activeTab'] = 'waitingForMarkingExamsTab';
        $data['exams'] = Exam::all();
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        return view('results.landing')->with($data);
    }



    public function DraftsIndex()
    {


        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'template';
        $data['activeTab'] = 'draftsTab';
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        return view('results.marking.drafts')->with($data);
    }


    public function draftEditable($year_id, $semester_id, $class_id, $stream_id, $exam_id, $subject_id)
    {

        $data['year_id'] = $year_id = $this->global->base64url_decode($year_id);
        $data['semester_id'] = $semester_id = $this->global->base64url_decode($semester_id);
        $data['class_id'] = $class_id = $this->global->base64url_decode($class_id);
        $data['stream_id'] = $stream_id = $this->global->base64url_decode($stream_id);
        $data['exam_id'] = $exam_id = $this->global->base64url_decode($exam_id);
        $data['subject_id'] = $subject_id = $this->global->base64url_decode($subject_id);

        $data['examInfo'] = Exam::find($exam_id);
        $data['class_info'] = SchoolClass::find($class_id)->name . ' ' . Stream::find($stream_id)->name;
        $data['semester'] = Semester::find($semester_id)->name;
        $data['subject'] = Subject::find($subject_id)->name;
        $data['year'] = AcademicYear::find($year_id)->name;

        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();
        $data['activeTab'] = 'draftsTab';

        return view('results.marking.drafts_editable')->with($data);
    }




    public function incompletedResultsDatatable(Request $request)
    {
        try {

            $user = auth()->user();
            $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();
            $incompletes = array();

            if ($user->hasRole('Admin')) {

                $incompletes = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                    ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                    ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                    ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                    ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                    ->join('exams', 'exams.id', '=', 'results.exam_id')
                    ->where('results.status', 'PENDING')
                    ->select('academic_years.name as acnm', 'results.academic_year_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id', 'results.semester_id', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.name as semester_name')
                    ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                    ->get();
            } else {

                if (count($assignments)) {


                   $incompletes = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                        ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                        ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                        ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                        ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                        ->join('exams', 'exams.id', '=', 'results.exam_id')
                        ->where('results.status', 'PENDING')
                        ->select('academic_years.name as acnm', 'results.academic_year_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id', 'results.semester_id', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.name as semester_name')
                        ->where(function ($query) use ($assignments) {
                            foreach ($assignments as $assignment) {
                                $query->orWhere(function ($innerQuery) use ($assignment) {
                                    $innerQuery->where('results.class_id', $assignment->class_id)
                                        ->where('results.stream_id', $assignment->stream_id)
                                        ->where('results.subject_id', $assignment->subject_id);
                                });
                            }
                        })
                        ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                        ->get();
                }
            }




            // $user = auth()->user();
            // $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();

            // if (auth()->user()->hasRole('Admin')) {
            //     $incompletes = Result::join('academic_years','results.academic_year_id','=','academic_years.id')
            //     ->join('semesters','results.semester_id', '=', 'semesters.id')
            //     ->join('school_classes','school_classes.id','=','results.class_id')
            //     ->join('subjects','results.subject_id','=','subjects.id')
            //     ->leftjoin('streams','streams.id','=','results.stream_id')
            //     ->join('exams','exams.id','=','results.exam_id')
            //     ->where('results.status','PENDING')
            //     ->select('academic_years.name as acnm','results.academic_year_id','results.class_id','results.exam_id','results.subject_id','results.stream_id','results.semester_id','school_classes.name as class_name','exams.name as exam_name','streams.name as stream_name','subjects.name as sbjctname','semesters.name as semester_name')
            //     ->groupBy(['results.academic_year_id','results.semester_id','results.class_id','results.exam_id','results.subject_id','results.stream_id'])
            //     ->get();
            // }else{


            //     $incompletes = Result::join('academic_years','results.academic_year_id','=','academic_years.id')
            //     ->join('semesters','results.semester_id', '=', 'semesters.id')
            //     ->join('school_classes','school_classes.id','=','results.class_id')
            //     ->join('subjects','results.subject_id','=','subjects.id')
            //     ->leftjoin('streams','streams.id','=','results.stream_id')
            //     ->join('exams','exams.id','=','results.exam_id')
            //     ->where('results.status','PENDING')
            //     ->select('academic_years.name as acnm','results.academic_year_id','results.class_id','results.exam_id','results.subject_id','results.stream_id','results.semester_id','school_classes.name as class_name','exams.name as exam_name','streams.name as stream_name','subjects.name as sbjctname','semesters.name as semester_name')
            //     ->where(function ($query) use ($assignments) {
            //         foreach ($assignments as $assignment) {
            //             $query->orWhere(function ($innerQuery) use ($assignment) {
            //                 $innerQuery->where('results.class_id', $assignment->class_id)
            //                     ->where('results.stream_id', $assignment->stream_id)
            //                     ->where('results.subject_id', $assignment->subject_id);
            //             });
            //         }
            //     })
            //     ->groupBy(['results.academic_year_id','results.semester_id','results.class_id','results.exam_id','results.subject_id','results.stream_id'])
            // ->get();



            // }




            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($incompletes)


                ->editColumn('class_id', function ($inc) {

                    return $inc->class_name;
                })

                ->editColumn('stream_id', function ($inc) {

                    return $inc->stream_name;
                })
                ->editColumn('exam_id', function ($inc) {

                    return $inc->exam_name;
                })
                ->editColumn('subject_id', function ($inc) {

                    return $inc->sbjctname;
                })
                ->editColumn('semester_id', function ($inc) {

                    return $inc->semester_name;
                })

                ->editColumn('academic_year_id', function ($inc) {

                    return $inc->acnm;
                })

                ->addColumn('action', function ($inc) {
                    return '<span>
             <a style="color:white" type="button" href="' . route("results.sytem.excel.incomplete.marks.editable", ['year_id' => $this->global->base64url_encode($inc->academic_year_id), 'semester_id' => $this->global->base64url_encode($inc->semester_id), 'class_id' => $this->global->base64url_encode($inc->class_id), 'stream_id' => $this->global->base64url_encode($inc->stream_id), 'exam_id' => $this->global->base64url_encode($inc->exam_id), 'subject_id' => $this->global->base64url_encode($inc->subject_id)]) . '" data-semester_id="' . $inc->semester_id . '" data-class_id="' . $inc->class_id . '" data-exam_id="' . $inc->exam_id . '" data-academic_year_id="' . $inc->academic_year_id . '" data-subject_id="' . $inc->subject_id . '" data-stream_id="' . $inc->stream_id . '"  class="btn text-white view-incomplete btn-info btn-sm"><i class="fa fa-eye"></i></a>
             | <button style="background:#377493; color:white" type="button" data-acdmc="' . $inc->academic_year_id . '" data-smst="' . $inc->semester_id . '" data-cls="' . $inc->class_id . '" data-strm="' . $inc->stream_id . '" data-extype="' . $inc->exam_id . '"  data-sbjct_id="' . $inc->subject_id . '" data-uuid="' . $inc->uuid . '" class="btn btn-sm finalize"> Finalize <i class="fa-solid fa-rocket"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function IncompleteMarkingIndex()
    {

        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'template';
        $data['activeTab'] = 'incompleteTab';
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        return view('results.marking.incomplete_marking')->with($data);
    }

    public function completedMarkingInDrive($year_id, $semester_id, $class_id, $stream_id, $exam_id, $subject_id)
    {
        // return "ni hapa";
        // $this->global->

        $data['year_id'] = $year_id = $this->global->base64url_decode($year_id);
        $data['semester_id'] = $semester_id = $this->global->base64url_decode($semester_id);
        $data['class_id'] = $class_id = $this->global->base64url_decode($class_id);
        $data['stream_id'] = $stream_id = $this->global->base64url_decode($stream_id);
        $data['exam_id'] = $exam_id = $this->global->base64url_decode($exam_id);
        $data['subject_id'] = $subject_id = $this->global->base64url_decode($subject_id);

        $data['examInfo'] = Exam::find($exam_id);
        $data['class_info'] = SchoolClass::find($class_id)->name . ' ' . Stream::find($stream_id)->name;
        $data['semester'] = Semester::find($semester_id)->name;
        $data['subject'] = Subject::find($subject_id)->name;
        $data['year'] = AcademicYear::find($year_id)->name;

        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();
        $data['activeTab'] = 'completeTab';

        return view('results.marking.completed_marking_indrive')->with($data);
    }


    public function completedMarkingInDriveDatatable(Request $request)
    {

        try {

            // return $request->all();

            $academic_year = $request->acdmcyear;
            $class_id = $request->class_id;
            $stream_id = $request->stream_id;
            $exam_type = $request->exam_type;
            $subject_id = $request->subject_id;
            $semester_id = $request->semester;

            $elevel = SchoolClass::find($class_id)->educationLevels->id;

            $examInfo = Exam::find($exam_type);

            $data['subject'] = Subject::find($subject_id);

            $results = Student::join('results', 'students.id', 'results.student_id')
                ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                ->select('results.score', 'results.grade_group_id', 'results.uuid', 'results.student_id', 'results.status', 'students.firstname', 'students.middlename', 'students.lastname')
                ->where('results.class_id', $class_id)
                ->where('results.semester_id', $semester_id)
                ->where('results.academic_year_id', $academic_year)
                ->where('results.status', 'COMPLETED')
                // ->where('results.full_name','FURAHA ABDALLAH MAKALLA')
                ->whereNull('results.deleted_at');

            if ($exam_type) {
                $results->where('results.exam_id', $exam_type);
            }

            if ($stream_id) {
                $results->where('results.stream_id', $stream_id);
                $data['stream'] = Stream::find($stream_id)->name;
            }

            if ($subject_id) {
                $results->where('results.subject_id', $subject_id);
            }

            foreach ($results->get() as $key => $result) {
                $students_result[$result->student_id]['score'] = $result->score;
                $uuids[$result->student_id]['uuid'] = $result->uuid;
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

            return DataTables::of($results)


                ->editColumn('class_id', function ($inc) {


                    return $inc->class_name;
                })

                ->addColumn('sn', function ($inc) {

                    return '';
                })

                ->addColumn('admission_no', function ($inc) {

                    return $inc->student_id;
                })

                ->addColumn('full_name', function ($inc) {
                    return $inc->firstname . ' ' . $inc->middlename . ' ' . $inc->lastname;
                })

                ->addColumn('percentage', function ($inc) use ($examInfo) {

                    return $this->global->marksToPercentage($inc->score, $examInfo);
                })

                ->addColumn('remarks', function ($inc) use ($examInfo, $elevel) {

                    if ($inc->score !== '-') {
                        $grade_collective = $this->global->getGrade($inc->score, $elevel, $examInfo, $inc->grade_group_id);
                        return $grade_collective['remarks'];
                    } else {
                        return '-';
                    }
                })

                ->addColumn('grade', function ($inc) use ($examInfo, $elevel) {

                    if ($inc->score !== '-') {

                        $grade_collective = $this->global->getGrade($inc->score, $elevel, $examInfo, $inc->grade_group_id);
                        return $grade_collective['grade'];
                    } else {
                        return '-';
                    }
                })

                ->addColumn('score', function ($inc) {

                    return $inc->score;
                })

                ->editColumn('stream_id', function ($inc) {

                    return $inc->stream_name;
                })
                ->editColumn('exam_id', function ($inc) {


                    return $inc->exam_name;
                })
                ->editColumn('subject_id', function ($inc) {

                    return $inc->sbjctname;
                })
                ->editColumn('semester_id', function ($inc) {

                    return $inc->semester_name;
                })

                ->editColumn('academic_year_id', function ($inc) {

                    return $inc->acnm;
                })

                ->addColumn('action', function ($inc) {
                    return '<span>
             <button type="button" data-uuid="' . $inc->uuid . '" class="btn btn-custon-four btn-primary btn-xs edit"><i class="fa fa-edit"></i></button>
         </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }





    /* COMPLETED SH*******T */

    public function completeMarkingIndex()
    {

        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['activeRadio'] = 'template';
        $data['activeTab'] = 'completeTab';
        $data['completedCount'] = $this->global->completedMarksCount();
        $data['inCompletedCount'] = $this->global->getIncompletedMarksCount();
        $data['waitingForMarkingCount'] = $this->global->waitingForMarkingCount();
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();

        return view('results.marking.completed_marking')->with($data);
    }

    public function Completedatatable(Request $request)
    {


        try {

            $user = auth()->user();
            $assignments = SubjectTeacher::where('teacher_id', $user->id)->get();
            $completes = array();

            if ($user->hasRole('Admin')) {

                $completes = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                    ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                    ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                    ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                    ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                    ->join('exams', 'exams.id', '=', 'results.exam_id')
                    ->where('results.status', 'COMPLETED')
                    ->select('academic_years.name as acnm', 'results.academic_year_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id', 'results.semester_id', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.name as semester_name')
                    ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                    ->get();
            } else {

                if (count($assignments)) {

                    $completes = Result::join('academic_years', 'results.academic_year_id', '=', 'academic_years.id')
                        ->join('semesters', 'results.semester_id', '=', 'semesters.id')
                        ->join('school_classes', 'school_classes.id', '=', 'results.class_id')
                        ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                        ->leftjoin('streams', 'streams.id', '=', 'results.stream_id')
                        ->join('exams', 'exams.id', '=', 'results.exam_id')
                        ->where('results.status', 'COMPLETED')
                        ->select('academic_years.name as acnm', 'results.academic_year_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id', 'results.semester_id', 'school_classes.name as class_name', 'exams.name as exam_name', 'streams.name as stream_name', 'subjects.name as sbjctname', 'semesters.name as semester_name')
                        ->where(function ($query) use ($assignments) {
                            foreach ($assignments as $assignment) {
                                $query->orWhere(function ($innerQuery) use ($assignment) {
                                    $innerQuery->where('results.class_id', $assignment->class_id)
                                        ->where('results.stream_id', $assignment->stream_id)
                                        ->where('results.subject_id', $assignment->subject_id);
                                });
                            }
                        })
                        ->groupBy(['results.academic_year_id', 'results.semester_id', 'results.class_id', 'results.exam_id', 'results.subject_id', 'results.stream_id'])
                        ->orderBy('results.created_at','desc')
                        ->get();
                }
            }



            $search = $request->get('search');

            if (!empty($search)) {
            }

            return DataTables::of($completes)


                ->editColumn('class_id', function ($inc) {


                    return $inc->class_name;
                })

                ->editColumn('stream_id', function ($inc) {

                    return $inc->stream_name;
                })
                ->editColumn('exam_id', function ($inc) {

                    return $inc->exam_name;
                })
                ->editColumn('subject_id', function ($inc) {

                    return $inc->sbjctname;
                })
                ->editColumn('semester_id', function ($inc) {

                    return $inc->semester_name;
                })



                ->editColumn('academic_year_id', function ($inc) {

                    return $inc->acnm;
                })

                ->addColumn('action', function ($inc) {
                    return '<span>
             <a style="color:white" type="button" href="' . route("results.sytem.complete.marking.indrive", ['year_id' => $this->global->base64url_encode($inc->academic_year_id), 'semester_id' => $this->global->base64url_encode($inc->semester_id), 'class_id' => $this->global->base64url_encode($inc->class_id), 'stream_id' => $this->global->base64url_encode($inc->stream_id), 'exam_id' => $this->global->base64url_encode($inc->exam_id), 'subject_id' => $this->global->base64url_encode($inc->subject_id)]) . '" data-semester_id="' . $inc->semester_id . '" data-class_id="' . $inc->class_id . '" data-exam_id="' . $inc->exam_id . '" data-academic_year_id="' . $inc->academic_year_id . '" data-subject_id="' . $inc->subject_id . '" data-stream_id="' . $inc->stream_id . '"  class="btn btn-custon-four text-white view-incomplete btn-info btn-sm"><i class="fa fa-eye"></i></a>
             |<button data-acdmc="' . $inc->academic_year_id . '" data-smst="' . $inc->semester_id . '" data-cls="' . $inc->class_id . '" data-strm="' . $inc->stream_id . '" data-extype="' . $inc->exam_id . '"  data-sbjct_id="' . $inc->subject_id . '" data-uuid="' . $inc->uuid . '"
             title="Return to Incomplete" type="button" data-uuid="' . $inc->uuid . '" class="btn btn-custon-four btn-warning revert btn-sm"><i class="fa-solid fa-rotate-left"></i>
             </button>|
             <a style="color:white" type="button" target="_blank" href="' . route("results.complete.print.preview", [
                        'year_id' => $this->global->base64url_encode($inc->academic_year_id),
                        'semester_id' => $this->global->base64url_encode($inc->semester_id),
                        'class_id' => $this->global->base64url_encode($inc->class_id),
                        'stream_id' => $this->global->base64url_encode($inc->stream_id),
                        'exam_id' => $this->global->base64url_encode($inc->exam_id),
                        'subject_id' => $this->global->base64url_encode($inc->subject_id)
                    ]) . '" data-semester_id="' . $inc->semester_id . '" data-class_id="' . $inc->class_id . '" data-exam_id="' . $inc->exam_id . '" data-academic_year_id="' . $inc->academic_year_id . '" data-subject_id="' . $inc->subject_id . '" data-stream_id="' . $inc->stream_id . '"
            class="btn btn-custon-four text-white print-mine btn-primary btn-sm"><i class="fa fa-print"></i></a>


         </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    // print preview for the complete results

    public function printPreview(
        $year_id,
        $semester_id,
        $class_id,
        $stream_id,
        $exam_id,
        $subject_id
    ) {

        // $examInfo = Exam::find($exam_id)

        $year_id = $this->global->base64url_decode($year_id);
        $semester_id = $this->global->base64url_decode($semester_id);
        $class_id = $this->global->base64url_decode($class_id);
        $stream_id = $this->global->base64url_decode($stream_id);
        $exam_id = $this->global->base64url_decode($exam_id);
        $subject_id = $this->global->base64url_decode($subject_id);


        $examInfo = Exam::find($exam_id);
        $subject = Subject::find($subject_id);
        $class = SchoolClass::find($class_id);
        $academic_year = AcademicYear::find($year_id);
        $stream = Stream::find($stream_id);
        $semester = Semester::find($semester_id);
        $school = SchoolProfile::get()->first();

        if ($school->school_logo) {

            // Extracting the file path from the link
            $filePath = str_replace('storage', 'app/public', $school->school_logo);

            // Getting the absolute file path
            $logo = storage_path($filePath);
            // $logo = asset('storage/' . $school->school_logo);
        }



        $teacher_info = SubjectTeacher::select('teacher_id')
            ->where('class_id', $class_id)
            ->where('stream_id', $stream_id)
            ->where('subject_id', $subject_id)
            ->first();

        if ($teacher_info) {
            $teacher = User::find($teacher_info->teacher_id);

            if ($teacher) {
                $teacher_name = $teacher->firstname . ' ' . $teacher->lastname;
            }
        }

        if ($subject) {
            $hod_info = Department::find($subject->department_id);

            if ($hod_info) {

                $hod_data = User::where('id', $hod_info->hod_id)->first();

                if ($hod_data) {
                    $hod_name = $hod_data->firstname . ' ' . $hod_data->lastname;
                }
            }
        }

        $results = Student::join('results', 'students.id', 'results.student_id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select('results.score', 'results.grade_group_id', 'results.uuid', 'results.student_id', 'results.status', 'students.firstname', 'students.middlename', 'students.lastname','students.gender', 'students.admission_no')
            ->where('results.class_id', $class_id)
            ->where('results.semester_id', $semester_id)
            ->where('results.academic_year_id', $year_id)
            ->where('results.stream_id', $stream_id)
            ->where('results.status', 'COMPLETED')
            ->whereNull('results.deleted_at')
            ->orderBy('students.gender','desc')
            ->orderBy( 'students.firstname');

        if ($exam_id) {
            $results->where('results.exam_id', $exam_id);
        }

        if ($subject_id) {
            $results->where('results.subject_id', $subject_id);
        }

        $students = $results->get();

        if ($school->school_logo) {
            $imageUrl = asset('storage/' . $school->school_logo);
        }

        $data = [
            'examInfo' => $examInfo,
            'subject' => $subject,
            'class' => $class,
            'students' => $students,
            'academic_year' => $academic_year,
            'stream' => $stream,
            'semester' => $semester,
            'teacher_name' => $teacher_name,
            'hod_name' => $hod_name,
            'school' => $school,
            // 'logo' => $logo
        ];

        $pdf = PDF::loadView('results.reports.printouts.preview_results', $data);

        // return $pdf->stream();
        // return $pdfContent = $pdf->output();

        return $pdf->stream($class->name.'-'.$stream->name.' subject report.pdf');
    }
}
