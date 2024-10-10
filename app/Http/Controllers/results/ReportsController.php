<?php

namespace App\Http\Controllers\results;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassTeacher;
use App\Models\ClastreamSubject;
use App\Models\Comment;
use App\Models\EscalationLevel;
use App\Models\Exam;
use App\Models\ExamReport;
use App\Models\GeneratedExamReport;
use App\Models\Grade;
use App\Models\GradeGroup;
use App\Models\PredefinedComment;
use App\Models\Result;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentResultReport;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\StudentSubjectsAssignment;

class ReportsController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }

    public function newIndex(Request $request)
    {

        $data['is_teacher'] = auth()->user()->getIsClassTeacher();
        $data['is_admin'] = auth()->user()->checkRole('Admin');

        return view('results.reports.new_index')->with($data);
    }


    public function escalationIndex($uuid)
    {

        $data['activeTab'] = 'escalationIndexTab';
        $data['uuid'] = $uuid;
        $data['generated_exam_report'] = GeneratedExamReport::where('uuid', $uuid)->first();
        return view('results.reports.generated.escalation_index')->with($data);
    }



    public function publishIndex($uuid)
    {

        $data['activeTab'] = 'escalationIndexTab';
        $data['uuid'] = $uuid;
        $data['generated_exam_report'] = GeneratedExamReport::where('uuid', $uuid)->first();
        return view('results.reports.generated.publish_index')->with($data);
    }



    public function generateClassReport_ojijo(

        // $academicYearId=null,
        // $termId = null,
        // $classId = null,
        // $subjectId = null,
        // $examId = null,
        // $streamId = null,
        Request $req

    ) {

        //  return 'now';


        $data['activeTab'] = 'generateReportTab';

        $academicYearId = $req->query('academic_year_id');
        $termId = $req->query('term_id');
        $classId = $req->query('class_id');
        $subjectId = $req->query('subject_id');
        $examId = $req->query('exam_id');
        $streamId = $req->query('stream_id');

        $streams = [];

        $data['academic_year_id'] = $academicYearId;
        $data['term_id'] = $termId;
        $data['class_id'] = $classId;
        $data['stream_id'] = $streamId;
        $data['subject_id'] = $subjectId;
        $data['exam_id'] = $examId;
        $data['reports'] = ExamReport::all();

        if ($classId) {
            $streams = SchoolClass::find($classId)->streams;
        }

        $data['terms'] = Semester::all();
        $assignments =  ClassTeacher::where('teacher_id', auth()->user()->id)->get();

        if ($assignments) {

            //  return 'tunafika apa';

            foreach ($assignments as $key => $assignment) {

                $data['assigned'][$assignment->class_id]['class_id'] = $assignment->class_id;
                $data['assigned'][$assignment->class_id]['class_name'] = SchoolClass::find($assignment->class_id)->name;
                $data['assigned'][$assignment->class_id]['stream_id'] = $assignment->stream_id;
                $data['assigned'][$assignment->class_id]['stream_name'] = Stream::find($assignment->stream_id)->name;
            }
        } elseif (auth()->user()->hasRole('Admin')) {

            //    return $school_classes = $data['school_classes'] =  SchoolClass::join('streams', 'school_classes.id', '=', 'streams.class_id')
            //         ->select(
            //             'school_classes.id as class_id',
            //             'streams.name as stream_name',
            //             'streams.id as stream_id',
            //             'school_classes.name as class_name'
            //         )->get();
        }

        $data['classes'] = SchoolClass::all();

        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] =  $streams;
        $data['academic_years'] = AcademicYear::all();

        return $data;

        return view('results.reports.index')->with($data);
    }

    public function generateClassReport(Request $req)
    {

        $data['activeTab'] = 'generateReportTab';

        $academicYearId = $req->query('academic_year_id');
        $termId = $req->query('term_id');
        $classId = $req->query('class_id');
        $subjectId = $req->query('subject_id');
        $examId = $req->query('exam_id');
        $streamId = $req->query('stream_id');

        $streams = [];

        $data['academic_year_id'] = $academicYearId;
        $data['term_id'] = $termId;
        $data['class_id'] = $classId;
        $data['stream_id'] = $streamId;
        $data['subject_id'] = $subjectId;
        $data['exam_id'] = $examId;
        $data['reports'] = ExamReport::all();

        // Fetch all classes regardless of user role
        $data['classes'] = SchoolClass::all();

        // Fetch all assignments for admins
        if (auth()->user()->hasRole('Admin')) {
            $assignments =  ClassTeacher::all();
        } else {
            // Fetch assignments for the logged-in teacher
            $assignments =  ClassTeacher::where('teacher_id', auth()->user()->id)->get();
        }

        foreach ($assignments as $key => $assignment) {
            $data['assigned'][$assignment->class_id]['class_id'] = $assignment->class_id;
            $data['assigned'][$assignment->class_id]['class_name'] = SchoolClass::find($assignment->class_id)->name;
            $data['assigned'][$assignment->class_id]['stream_id'] = $assignment->stream_id;
            $data['assigned'][$assignment->class_id]['stream_name'] = Stream::find($assignment->stream_id)->name;
        }

        // Fetch other necessary data
        $data['terms'] = Semester::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::orderBy('created_at', 'DESC')->get();

    //    return $data['exams'] = Exam::all()->orderBy('created_at', 'DESC');
        $data['academic_years'] = AcademicYear::all();

        // If classId is provided, get streams for that class
        if ($classId) {
            $streams = SchoolClass::find($classId)->streams;
        }

        return view('results.reports.index', $data);
    }



    public function load(Request $req)
    {
        // return $req->all();
        $class_id = $req->class_id;
        $exam_type = $req->exam_type;
        $semester = $req->semester;
        $stream_id = $req->stream_id;
        $subject_id = $req->subject_id;
        $academic_year = $req->acdmcyear;

        // return $req->all();

        $elevel = SchoolClass::find($class_id)->education_level_id;

        $data['class_id'] =  $class_id;
        $data['exam_type'] = $exam_type;
        $data['semester'] = $semester;
        $data['stream_id'] = $stream_id;
        $data['academic_year'] = $academic_year;
        $data['elevel']  = $elevel;


        $data['year_name'] = AcademicYear::find($academic_year)->name;
        $data['semester_name'] = Semester::find($semester)->name;
        $data['class_name'] = SchoolClass::find($class_id)->name . '' . Stream::find($stream_id)->name;
        $exam_report = ExamReport::find($req->report_name);
        $data['report'] = $exam_report ? $exam_report->name : '';


        $subjects_og = ClastreamSubject::join('subjects', 'subjects.id', '=', 'clastream_subjects.subject_id')->join('school_classes', 'school_classes.id', '=', 'clastream_subjects.class_id')
            ->join('streams', 'streams.id', '=', 'clastream_subjects.stream_id')
            ->select('streams.name as stream_name', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
            ->where(['clastream_subjects.class_id' => $class_id, 'clastream_subjects.stream_id' => $stream_id])->get();

        $subjects = ClastreamSubject::join('subjects', 'subjects.id', '=', 'clastream_subjects.subject_id')
            ->join('school_classes', 'school_classes.id', '=', 'clastream_subjects.class_id')
            ->join('streams', 'streams.id', '=', 'clastream_subjects.stream_id')
            ->join('education_levels', 'education_levels.id', '=', 'school_classes.education_level_id') // Join with education_levels table
            ->select(
                'streams.name as stream_name',
                'school_classes.name as class_name',
                'subjects.uuid',
                'subjects.subject_type',
                'subjects.id as sbjct_id',
                'subjects.name as subject_name',
                'streams.id as stream_id',
                'school_classes.id as class_id',
                'subjects.code as sbjct_code',
                'education_levels.id as level'
            )
            ->where([
                'clastream_subjects.class_id' => $class_id,
                'clastream_subjects.stream_id' => $stream_id
            ])
            ->get();


        $subjects_total = count($subjects);

        $load_students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
            ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
            ->select('students.id', 'students.admission_no', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
            ->where('students.class_id', $class_id);


        if ($stream_id) {

            $load_students->where('students.stream_id', $stream_id);
        }

        $students = $load_students->get();

        $pre_info = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
            ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
            ->leftjoin('results', 'students.id', '=', 'results.student_id')
            ->leftjoin('academic_years', 'academic_years.id', '=', 'results.academic_year_id')
            ->leftjoin('semesters', 'academic_years.id', '=', 'semesters.academic_year_id')
            ->leftjoin('exams', 'results.exam_id', '=', 'exams.id')
            ->leftjoin('subjects', 'results.subject_id', 'subjects.id')
            ->where('results.academic_year_id', $academic_year)
            ->where('results.class_id', $class_id)
            ->whereIn('results.exam_id', $exam_type)
            ->where('semesters.id', $semester)
            ->whereNull('results.deleted_at')
            ->select('results.score', 'results.class_id', 'results.student_id', 'results.grade_group_id', 'subjects.name as subject_name', 'subjects.subject_type', 'results.full_name', 'students.id', 'students.id as admission_no', 'exam_id', 'results.stream_id', 'results.subject_id', 'results.grade_group_id')
            ->where('results.status', 'COMPLETED')
            ->groupBy('students.id', 'exams.id', 'subjects.id');

        if ($stream_id) {
            $pre_info->where('results.stream_id', $stream_id);
        }
        if ($subject_id) {
            $pre_info->where('results.subject_id', $subject_id);
        }

        $for_my_grade = '';

        $results = $pre_info->get();

        if (count($exam_type) == 1) {



            $data['examInfo'] = $examInfo = Exam::whereIn('id', $exam_type)->first();


            if ($req->subject_id && $req->exam_type) {


                $data['dynamicColumns'] = [
                    ['data' => 'sn', 'name' => 'sn'],
                    ['data' => 'admission_no', 'name' => 'admission_no', 'orderable' => false],
                    ['data' => 'full_name', 'name' => 'full_name'],
                    ['data' => 'score', 'name' => 'score'],
                    ['data' => 'percentage', 'name' => 'percentage', 'orderable' => false],
                    ['data' => 'grade', 'name' => 'grade'],
                    ['data' => 'remarks', 'name' => 'remarks', 'orderable' => false, 'searchable' => false,],


                ];


                $base_html = '
           <div style="margin-top:2rem" class="">
           <div class="row elevation-2">
           <div class="col-md-12" style="margin-bottom: 1rem">
           <span style="float:right; margin-right: 3.6rem; margin-top: 2rem;">
           <a href="javascript:void(0)" title="Generate Report"  style="color:white;" data-academic-year="' . $academic_year . '" data-class-id="' . $class_id . '" data-semester="' . $semester . '" data-subject_id="' . $exam_type . '" data-elevel="' . $elevel . '"  data-stream-id="' . $stream_id . '"  class="btn generate_dynamic_single_report btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate </a>
           <a href="javascript:void(0)" title="excel"  style="color:white; display:none"  class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i> Excel </a>
           <a target="_blank" href="' . route('results.single.subject.examtype.checked.pdf', [$academic_year, $class_id, $semester, $subject_id, $exam_type, $elevel, $stream_id]) . '" title="pdf"  style="color:white;"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
           </span>
           </div>

               <div class="col-md-12" style="padding-left: 5rem;  padding-right: 5rem;">
                   <table class="table table-bordered x-editor-custom" id="table" style="width:100%">
                       <thead>
                           <tr>
                               <th>SN</th>
                               <th style="text-align: center; width:20rem">Admission Number</th>
                               <th>Full Name</th>
                               <th>marks/' . $examInfo->total_marks . '</th>
                               <th> % </th>
                               <th> Grade </th>
                               <th> Remarks </th>
                           </tr>

                       </thead>
                   </table>
               </div>
           </div>
       </div>
           ';
            } elseif (!$req->subject_id && $req->exam_type) {

                $colspan = 0;
                //    return 'hia';

                foreach ($subjects as $key => $subject) {

                    $sbjct_columns[] = [
                        'data' => $subject->subject_name,
                        'name' => $subject->subject_name,
                        'subject_id' => $subject->sbjct_id,
                    ];
                    $colspan += 1;
                }

                $data['sbjct_columns'] = $sbjct_columns;

                $matokeo = array();

                foreach ($students as $key => $student) {

                    $matokeo[$student->id]['full_name'] = $student->full_name;
                    $matokeo[$student->id]['admission_no'] = $student->id;
                    $points_array = array();
                    $score_array = array();

                    foreach ($results as $key => $dt) {

                        if ($dt->admission_no == $student->id) {

                            foreach ($subjects as $key => $subject) {
                                if ($dt->subject_id == $subject->sbjct_id) {

                                    $score = $dt->score;

                                    $group_id = $dt->grade_group_id;
                                    $for_my_grade = $group_id;

                                    $score_percent = $this->global->marksToPercentage($dt->score, $examInfo);

                                    $grade = $this->global->getGrade($score, $elevel, $examInfo, $group_id)['grade'];


                                    $points = $this->global->getGrade($score, $elevel, $examInfo, $group_id)['points'];

                                    $remarks = $this->global->getGrade($score, $elevel, $examInfo, $group_id)['remarks'];
                                    $matokeo[$student->id]['results'][$subject->sbjct_id] = ['score' => $dt->score, 'subject_id' => $subject->sbjct_id, 'name' => $subject->subject_name, 'percent' => $score_percent, 'grade' => $grade, 'remarks' => $remarks];

                                    if ($points != 'N/A') {

                                        if ($subject->subject_type == 'PRINCIPAL') {
                                            array_push($points_array, $points);
                                        }
                                        array_push($score_array, $score_percent);
                                    }
                                }
                            }
                        }
                    }



                    $theAvg = $this->global->generateAvg($score_array);
                    $matlab = $this->global->generateDivisions($elevel, $points_array);
                    $matokeo[$student->id]['DIVISION'] = $matlab['division'];
                    $matokeo[$student->id]['POINTS'] = $matlab['points'];
                    $matokeo[$student->id]['CODE'] = $matlab['code'];
                    $matokeo[$student->id]['REMARKS'] = $matlab['remarks'];
                    $matokeo[$student->id]['AVG'] = $theAvg;

                    // function getExamResultsAvg($){
                    //AVG BASED ON WHAT CRITERIA REALLY  // ALL? OR ONLY THE PASSED ONES
                    // }
                    $matokeo[$student->id]['SP'] = '';
                    $matokeo[$student->id]['CP'] = '';
                }

                if (!count($results)) {

                    $data['base_html'] = '<div class="mg-y-120">
        <div class="card mx-auto wd-300 text-center bd-transparent bg-transparent">
        <img style="width:20em" src="' . asset('assets/images/nodata.png') . '">
           <p class="lead">Seems you\'re looking for results that doesn\'t exist.</p>
        </div>
     </div>';
                    return  response($data);
                }


                $data['matokeo'] = $matokeo;

                $data['processedData'] = $this->processData($matokeo);
                $data['processGradeAverages'] = $this->processGradeAverages($matokeo);
                $data['divisionCount'] = collect($matokeo)
                    ->pluck('DIVISION')
                    ->countBy()
                    ->mapWithKeys(function ($count, $division) {
                        return ["Division $division" => $count];
                    })
                    ->toArray();

                $data['gradeLabels'] = Grade::where(['group_id' => $for_my_grade, 'education_level_id' => $elevel])->pluck('name');

                $indexToInsert = 2;
                $colspan = $colspan * 3;
                $data['report_name'] = $req->report_name;
                $data['colspan'] = $colspan;
                $data['predefined_comments'] =  $predefined_comments = PredefinedComment::all();
                $data['for_my_grade'] = $for_my_grade;

                $view = view('results.reports.preview_single_exam_all_subjects', $data)->render();
                return response(['base_html' => $view]);
            } elseif ($class_id && !$subject_id && !$exam_type && $semester && $academic_year) {

                $sbjct_columns = [];

                $sbjcts_html = '';

                $colspan = 0;

                $data['dynamicColumns'] = [
                    ['data' => 'sn', 'name' => 'sn'],
                    ['data' => 'gender', 'name' => 'gender'],
                    ['data' => 'full_name', 'name' => 'full_name'],
                    ['data' => 'admission_no', 'name' => 'admission_no', 'orderable' => false],
                    [
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                    ],

                ];


                foreach ($subjects as $key => $subject) {

                    $sbjcts_html .= '<th data-subject_uuid="' . $subject->uuid . '">' . $subject->code . ' </th>';
                    $code  =   strtolower(str_replace(' ', '_', $subject->name));
                    $sbjct_columns[] = [
                        'data' => $code,
                        'name' => $code,
                    ];
                    $colspan += 1;
                }

                // return  $sbjct_columns;

                $indexToInsert = 3; // Adjust this index as needed
                array_splice($data['dynamicColumns'], $indexToInsert + 1, 0, $sbjct_columns);



                $base_html = '

                 <div class="row">
                 <div class="col-lg-12" style="margin-bottom: 5rem">
                <div class="dashtwo-order-list shadow-reset">
                 <div class="row elevation-2">
                 <div class="col-md-12" style="margin-bottom: 1rem">
                 <span style="float:right">
                 <a href="javascript:void(0)" title="excel"  style="color:white"  class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i> Excels1 </a>
                 <a href="javascript:void(0)" title="pdf"  style="color:white"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
                 </span>
                 </div>

                     <div class="col-md-12">
                         <table class="table table-bordered" id="table" style="width:100%">
                             <thead>
                                 <tr>
                                     <th rowspan="2">SN</th>
                                     <th class="text-center" rowspan="2">FULL NAME</th>
                                     <th rowspan="2"> Gender </th>
                                     <th rowspan="2" style="text-align: center; width:20rem">ADMISSION NUMBER</th>
                                     <th style="text-align:center" class="text-center" colspan="' . $colspan . '">
                                     TERM 1
                                     </th>
                                     <th rowspan="2"> action </th>
                                 </tr>
                                 <tr>

                                 ' . $sbjcts_html . '


                                 </tr>


                             </thead>
                         </table>
                     </div>



                 </div>
             </div>
                 ';

                $data['subjects'] = $subjects;
            }
            $data['base_html'] = $base_html;
            return  response($data);
        } elseif (count($exam_type) > 1) {




            /* MULTIPLE EXAM TYPES SELECTED */
            /* **************************************************************************************************************************************************************** */
            /* tunaanzia hapa */





            $data['predefined_comments']  = $predefined_comments = PredefinedComment::all();

            $sbjct_columns = [];
            $sbjcts_html = '';
            $colspan = 0;

            // return $subjects;


            $data['examodel'] = Exam::class;

            $data['exam_type'] = $exam_type;


            //   return $subjects;
            $exam_types_html = '';
            $exam_type_columns = array();

            foreach ($subjects as $key => $subject) {
                // return $subject;

                $code  =   strtolower(str_replace(' ', '_', $subject->sbjct_name));
                $subjct_span = 0;
                $remarks = 'N/A';
                foreach ($exam_type as $key => $exam) {

                    $exam_model = Exam::find($exam);
                    $subjct_span += 1;

                    $exam_type_columns[] = [
                        'subject_id' => $subject->sbjct_id,
                        'exam' => $exam_model,
                    ];
                }
                //   return $exam_types_html;
                $exam_types_html .= '<th style="text-align:center" data-subject_id=""  class="text-center">AVG %</th> <th style="text-align:center" data-subject_id=""  class="text-center">GRADE</th> <th style="text-align:center" data-subject_id=""  class="text-center">POINTS</th>';
                $subjct_span += 3;

                $sbjcts_html .= '<th style="text-align:center" colspan="' . $subjct_span . '" data-subject_uuid="' . $subject->uuid . '">' . $subject->sbjct_code . ' </th>';

                $sbjct_columns[] = [
                    'data' => $subject->sbjct_code,
                    'name' => $subject->subject_name,
                    'subject_id' => $subject->sbjct_id,
                    'subject_span' => $subjct_span,
                    'subject_uuid' => $subject->uuid
                ];
                $colspan += 1;
            }

            $exams = Exam::whereIn('id', $exam_type)->get();

            $total_score_exams = 0;
            foreach ($exams as $key => $exam) {

                $total_score_exams += $exam->total_marks;
            }

            $subject_results_arr = [];
            $sum = 0;

            $pre_info = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                ->leftjoin('results', 'students.id', '=', 'results.student_id')
                ->leftjoin('academic_years', 'academic_years.id', '=', 'results.academic_year_id')
                ->leftjoin('semesters', 'academic_years.id', '=', 'semesters.academic_year_id')
                ->leftjoin('exams', 'results.exam_id', '=', 'exams.id')
                ->leftjoin('subjects', 'results.subject_id', 'subjects.id')
                ->where('results.academic_year_id', $academic_year)
                ->where('results.class_id', $class_id)
                ->whereIn('results.exam_id', $exam_type)
                ->where('semesters.id', $semester)
                ->select('results.score', 'results.class_id', 'subjects.subject_type', 'results.grade_group_id', 'results.student_id', 'subjects.name as subject_name', 'subjects.subject_type', 'results.full_name', 'students.id', 'students.id as admission_no', 'exam_id', 'results.stream_id', 'results.subject_id', 'results.grade_group_id')
                ->where('results.status', 'COMPLETED')
                ->groupBy('students.id', 'exams.id');

            if ($stream_id) {
                $pre_info->where('results.stream_id', $stream_id);
            }

            // return $subject_id;

            if ($subject_id) {
                $pre_info->where('results.subject_id', $subject_id);
            }

            foreach ($results as $result) {

                if (isset($result)) {

                    $for_my_grade = $result->grade_group_id;
                    $score = isset($result->score) ? $result->score : 0;
                    if ($score != '-') {

                        $sum += intval($score);
                    }


                    // return $exam_type;
                    // foreach($exam_type as $exam)
                    // {
                    //     $ex = Exam::where('id',26)->first();
                    //     return $ex->is_dp;
                    // }
                    //  return $exam_type;
                    $average = $this->getAverage($result->admission_no, $result->subject_id, $exam_type, $result->grade_group_id, $elevel, $pre_info)['average'];
                    //
                    $grade = $this->getAverage($result->admission_no, $result->subject_id, $exam_type, $result->grade_group_id, $elevel, $pre_info)['grade'];
                    $remarks = $this->getAverage($result->admission_no, $result->subject_id, $exam_type, $result->grade_group_id, $elevel, $pre_info)['remarks'];
                    $points = $this->getAverage($result->admission_no, $result->subject_id, $exam_type, $result->grade_group_id, $elevel, $pre_info)['points'];

                    $subject_results_arr[$result->admission_no][$result->subject_id][$result->exam_id] = $score;
                    $subject_results_arr[$result->admission_no][$result->subject_id]['AVG'] = $average;
                    $subject_results_arr[$result->admission_no][$result->subject_id]['GRADE'] = $grade;
                    $subject_results_arr[$result->admission_no][$result->subject_id]['POINT'] = $points;
                    $subject_results_arr[$result->admission_no][$result->subject_id]['REMARKS'] = $remarks;
                    $subject_results_arr[$result->admission_no][$result->subject_id]['TYPE'] = $result->subject_type;
                    $subject_results_arr[$result->admission_no]['grade_group'] = $result->grade_group_id;
                }
            }


            $table_items = [];


            foreach ($students as $student) {

                $total_avg = 0;
                $total_points = array();
                if (isset($subject_results_arr[$student->id])) {

                    $fetch = $subject_results_arr[$student->id];

                    for ($k = 0; $k < count($sbjct_columns); $k++) {


                        $subject_id = $sbjct_columns[$k]['subject_id'];

                        $results = isset($fetch[$subject_id]) ? $fetch[$subject_id] : array();

                        if (count($results)) {

                            $types_ex = $req->exam_type;
                            foreach ($types_ex as $key => $column) {

                                $score = '-';
                                if (isset($results[$column])) {
                                    $score =  floatval($results[$column]) ? floatval($results[$column]) : 0;
                                    $point = floatval($results['POINT']) ? floatval($results['POINT']) : 0;
                                    $total_avg += $score;
                                }
                            }

                            if ($point != 'N/A' || $point != '-') {

                                if ($results['TYPE'] == 'PRINCIPAL') {
                                    array_push($total_points, $point);
                                }
                                // array_push($score_array,$score_percent);

                            }

                            //    $total_points += $point;
                        }
                    }
                }


                $matlab = $this->global->generateDivisions($elevel, $total_points);

                $avg = round($total_avg / ($total_score_exams * count($sbjct_columns)) * 100);
                $group_id = $fetch['grade_group'];
                $grade = $this->global->getAverageGrade($avg, $elevel, $group_id)['grade'];
                // $remarks =  $this->global->getAverageGrade($avg,$elevel,$group_id)['remarks'];

                // $theAvg = $this->global->generateAvg($score_array);
                // $matlab = $this->global->generateDivisions($elevel,$points_array);
                // $matokeo[$student->id]['DIVISION'] = $matlab['division'];
                // $matokeo[$student->id]['POINTS'] = $matlab['points'];
                // $matokeo[$student->id]['CODE'] = $matlab['code'];
                // $matokeo[$student->id]['REMARKS'] = $matlab['remarks'];
                // $matokeo[$student->id]['AVG'] = $theAvg;


                // if ($points != 'N/A' ) {

                //     if ($subject->subject_type == 'PRINCIPAL') {
                //         array_push($points_array, $points);
                //     }
                //     array_push($score_array,$score_percent);

                //     }




                $table_items[$student->id] = [
                    'admission_no' => $student->id,
                    'full_name' => $student->full_name,
                    'results' => isset($subject_results_arr[$student->id]) ? $subject_results_arr[$student->id] : [],
                    'AVG' => $avg,
                    'GRADE' => $grade,
                    'CODE' => $matlab['code'],
                    'REMARKS' => $remarks,
                    'POINTS' => $matlab['points']
                ];
            }



            $data['jt'] = $jt = encrypt($table_items);
            $indexToInsert = 2;
            $colspan = $colspan  * $subjct_span;
            $exam_type = encrypt($exam_type);


            $data['subject_id'] = $req->subject;
            $data['subjects'] = $subjects;
            $data['students'] = $students;
            $data['colspan'] = $colspan;
            $data['sbjct_columns'] = $sbjct_columns;
            $data['exam_type_columns'] = $exam_type_columns;
            $data['table_items'] = $table_items;
            $data['report_id'] = $req->report_name;
            $data['exam_types'] = $req->exam_type;
            $data['for_my_grade'] = $for_my_grade;

            $view = view('results.reports.preview_multiple_exam_all_subjects', $data)->render();
            return response(['base_html' => $view]);

            // return $table_items;

            // return $students;

            foreach ($students as $key => $student) {
                $avg = 0;
                $checkpoints = 0;

                $tbody .= ' <tr>
                        <td> ' . ++$key . ' </td>
                        <td>' . $table_items[$student->id]['admission_number'] . ' </td>
                        <td>' . $table_items[$student->id]['full_name'] . '</td>';
                // return $table_items[$student->id]['results'];
                if (count($table_items[$student->id]['results'])) {

                    for ($k = 0; $k < count($sbjct_columns); $k++) {
                        // return $sbjct_columns;
                        $types_ex = $req->exam_type;
                        foreach ($types_ex as $key => $column) {
                            $score = '-';
                            $check['AVG'] = '-';
                            $check['POINT'] = '-';
                            $check['GRADE'] = '-';

                            // return $table_items[$student->id]['results'];
                            // [$sbjct_columns[$k]['subject_id']];


                            if (isset($table_items[$student->id]['results'][$sbjct_columns[$k]['subject_id']])) {
                                $check = $table_items[$student->id]['results'][$sbjct_columns[$k]['subject_id']];
                                if (isset($check[$column])) {
                                    $score =  $check[$column];
                                }
                            }
                            $tbody .= '<td> ' . $score . ' </td>';
                        }



                        $avg += floatval($check['AVG']);
                        $checkpoints += floatval($check['POINT']);

                        $tbody .= '<td> ' . $check['AVG'] . ' </td> <td> ' . $check['GRADE'] . ' </td> <td> ' . $check['POINT'] . ' </td>';
                    }

                    $tbody .= ' <td>  ' . $table_items[$student->id]['avg'] . ' </td> <td> ' . $table_items[$student->id]['grade'] . ' </td> <td> ' . $table_items[$student->id]['division'] . ' </td>  <td> ' . $table_items[$student->id]['points'] . ' </td> <td>  <select name="ct_comment[' . $student->id . ']" class="form-control form-control-sm"> ' . $options . '  </select> </div> </td>   </tr>';
                }
            }


            $base_html .= $tbody . '</tbody>
        </table>';



            $base['base_html'] = $base_html;
            return  response($base);

            return $base_html;
        }
    }

    private function processData($studentsData)
    {
        $subjects = [];

        // Iterate over each student
        foreach ($studentsData as $studentId => $student) {

            if (isset($student['results'])) {

                foreach ($student['results'] as $subject) {
                    // Extract subject name and grade
                    $subjectName = $subject['name'];
                    $grade = $subject['grade'];

                    // Initialize the subjects array if not exists
                    if (!isset($subjects[$subjectName])) {
                        $subjects[$subjectName] = [];
                    }

                    // Increment the grade count for the subject
                    if (!isset($subjects[$subjectName][$grade])) {
                        $subjects[$subjectName][$grade] = 1;
                    } else {
                        $subjects[$subjectName][$grade]++;
                    }
                }
            }
        }

        return $subjects;
    }


    private function processDataIndrive($studentsData)
    {
        $subjects = [];

        // return $studentsData;


        // Iterate over each student
        foreach ($studentsData as $studentId => $student) {

            $students_meta = json_decode($student->metadata);

            return   $students_meta = isset(json_decode($student->metadata)->results) ? json_decode($student->metadata)->results : array();




            foreach ($students_meta as $subject) {
                $subjectName = $subject->name;
                $grade = $subject->grade;
                if (!isset($subjects[$subjectName])) {
                    $subjects[$subjectName] = [];
                }
                if (!isset($subjects[$subjectName][$grade])) {
                    $subjects[$subjectName][$grade] = 1;
                } else {
                    $subjects[$subjectName][$grade]++;
                }
            }
        }

        return $subjects;
    }


    private function processGradeAveragesIndrive($studentsData)
    {


        $subjectAverages = [];

        foreach ($studentsData as $studentId => $student) {

            return  $students_meta = isset(json_decode($student->metadata)->results)  ?  json_decode($student->metadata)->results : array();

            foreach ($students_meta as $subjectId => $subject) {
                $subjectName = $subject->name;
                $percent = $subject->percent;

                if (!isset($subjectAverages[$subjectName])) {
                    $subjectAverages[$subjectName] = ['total' => 0, 'count' => 0];
                }

                if ($percent != 'N/A') {

                    $subjectAverages[$subjectName]['total'] += $percent;
                }
                $subjectAverages[$subjectName]['count']++;
            }
        }



        // Calculate average for each subject
        foreach ($subjectAverages as $subjectName => $averageData) {
            $subjectAverages[$subjectName]['average'] = round($averageData['total'] / $averageData['count']);
        }
        arsort($subjectAverages);

        return $subjectAverages;
    }


    private function processGradeAverages($studentsData)
    {


        $subjectAverages = [];

        foreach ($studentsData as $studentId => $student) {

            if (isset($student['results'])) {

                foreach ($student['results'] as $subjectId => $subject) {
                    $subjectName = $subject['name'];
                    $percent = $subject['percent'];

                    if (!isset($subjectAverages[$subjectName])) {
                        $subjectAverages[$subjectName] = ['total' => 0, 'count' => 0];
                    }

                    if ($percent != 'N/A') {

                        $subjectAverages[$subjectName]['total'] += $percent;
                    }
                    $subjectAverages[$subjectName]['count']++;
                }
            }
        }



        // Calculate average for each subject
        foreach ($subjectAverages as $subjectName => $averageData) {
            $subjectAverages[$subjectName]['average'] = round($averageData['total'] / $averageData['count']);
        }
        arsort($subjectAverages);

        return $subjectAverages;
    }


    public function getResultForStudentSubjectExam($student_id, $subject_id, $exam_type_id, $data)
    {


        $rows = '';
        foreach ($data as $key => $dt) {

            if ($student_id == $dt->admission_no && $subject_id == $dt->subject_id && $exam_type_id == $dt->exam_id) {
                $rows .=   '<td  data-exam_id="' . $dt->exam_id . '">exam-id ' . $dt->exam_id . ' - subjct_id ' . $dt->subject_id . '- ' . $dt->score . ' </td> ';
            }
        }
        return $rows;
    }

    function getAverage_og($admission_no, $subject_id, $exam_type, $grade_group_id, $elevel, $data)
    {
        $sum = 0;
        $total_scores = 0;


        for ($i = 0; $i < count($exam_type); $i++) {

            $exam = Exam::find($exam_type[$i]);

            $sum +=  $exam->total_marks;
            //  $under = $exam_type->uder;
            //  $avg_final += 80/exam->total_marks * exam->under;
            // 80/100 * 80 + 20/20 * 20

            $exam_id = intval($exam_type[$i]);

            $model = with(clone $data)->where('results.student_id', $admission_no)->where('results.subject_id', $subject_id)->where('results.exam_id', $exam_id)->groupBy('subjects.id')->first();

            if ($model) {
                // 80/exam->total_marks * exam->under
                $score = floatval($model->score);

                if ($score != 'x' || $score != 's' || $score != '-') {

                    $total_scores += $score;
                }
                //  return $total_scores;
                //  $score = 0;

            }

            // return $total_scores;


        }

        // return floatval($sum);

        $average = round((floatval($total_scores) / floatval($sum) * 100));
        $average ? $average  : '-';

        $grade = $this->global->getAverageGrade($average, $elevel, $grade_group_id)['grade'];
        $points = $this->global->getAverageGrade($average, $elevel, $grade_group_id)['points'];
        $remarks = $this->global->getAverageGrade($average, $elevel, $grade_group_id)['remarks'];
        $entry['average'] = $average;
        $entry['grade'] = $grade;
        $entry['points'] = $points;
        $entry['remarks'] = $remarks;
        return $entry;
    }



    // final get average
    function getAverage($admission_no, $subject_id, $exam_type, $grade_group_id, $elevel, $data)
    {
        $dp_exam_ids = [];
        $non_dp_exam_ids = [];
        $dp_total_scores = 0;
        $non_dp_total_scores = 0;
        $dp_total_max_marks = 0;
        $non_dp_total_max_marks = 0;

        // Separate exams based on is_dp value
        foreach ($exam_type as $exam_id) {
            $exam = Exam::find($exam_id);
            if ($exam->is_dp == 1) {
                $dp_exam_ids[] = $exam_id;
                $dp_total_max_marks += $exam->total_marks;
            } else {
                $non_dp_exam_ids[] = $exam_id;
                $non_dp_total_max_marks += $exam->total_marks;
            }
        }

        // Calculate total scores for DP exams
        foreach ($dp_exam_ids as $exam_id) {
            $model = with(clone $data)
                ->where('results.student_id', $admission_no)
                ->where('results.subject_id', $subject_id)
                ->where('results.exam_id', $exam_id)
                ->groupBy('subjects.id')
                ->first();

            if ($model) {
                $score = floatval($model->score);
                if ($score != 'x' && $score != 's' && $score != '-') {
                    $dp_total_scores += $score;
                }
            }
        }

        // Calculate total scores for non-DP exams
        foreach ($non_dp_exam_ids as $exam_id) {
            $model = with(clone $data)
                ->where('results.student_id', $admission_no)
                ->where('results.subject_id', $subject_id)
                ->where('results.exam_id', $exam_id)
                ->groupBy('subjects.id')
                ->first();

            if ($model) {
                $score = floatval($model->score);
                if ($score != 'x' && $score != 's' && $score != '-') {
                    $non_dp_total_scores += $score;
                }
            }
        }

        // Calculate average based on the scenarios
        if (!empty($dp_exam_ids) && !empty($non_dp_exam_ids)) {
            // Scenario 3: Both DP and non-DP exams exist
            $dp_weight = 0.4;
            $non_dp_weight = 0.6;
            $dp_average = $dp_total_max_marks > 0 ? ($dp_total_scores / $dp_total_max_marks) * $dp_weight * 100 : 0;
            $non_dp_average = $non_dp_total_max_marks > 0 ? ($non_dp_total_scores / $non_dp_total_max_marks) * $non_dp_weight * 100 : 0;
            $average = round($dp_average + $non_dp_average);
            $average = $average;
        } elseif (!empty($dp_exam_ids)) {
            // Scenario 1: Only DP exams exist
            $average = round(($dp_total_scores / $dp_total_max_marks) * 100);
            $average ? $average  : '-';
        } else {
            // Scenario 2: Only non-DP exams exist
            $average = round(($non_dp_total_scores / $non_dp_total_max_marks) * 100);
            $average ? $average  : '-';
        }

        $grade = $this->global->getAverageGrade($average, $elevel, $grade_group_id)['grade'];
        $points = $this->global->getAverageGrade($average, $elevel, $grade_group_id)['points'];
        $remarks = $this->global->getAverageGrade($average, $elevel, $grade_group_id)['remarks'];

        $entry['average'] = $average;
        $entry['grade'] = $grade;
        $entry['points'] = $points;
        $entry['remarks'] = $remarks;

        return $entry;
    }








    public function datatable(Request $req)
    {

        try {


            // return $req->all();
            $class_id = $req->class_id;
            $exam_type = $req->exam_type;
            $semester = $req->semester;
            $stream_id = $req->stream_id;
            $subject_id = $req->subject_id;
            $academic_year = $req->acdmcyear;
            $subjects = $req->subjects;
            $elevel = $req->elevel;

            $students = SchoolClass::find($class_id)->students;

            $students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
                ->select('students.id', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
                ->where('students.class_id', $class_id);


            if ($stream_id) {

                $students->where('students.stream_id', $stream_id);
            }


            if (count($exam_type) == 1) {

                $exam_type = $exam_type[0];

                $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';



                /* SUBJECT SELECTED && EXAM TYPE REPORT */

                if ($subject_id && $exam_type) {

                    $results = Student::leftjoin('results', 'students.id', 'results.student_id')
                        ->join('subjects', 'results.subject_id', '=', 'subjects.id')
                        ->select('results.score', 'results.uuid', 'results.student_id', 'results.grade_group_id')
                        ->where('results.class_id', $class_id)
                        ->where('results.semester_id', $semester)
                        ->where('results.academic_year_id', $academic_year);

                    if ($exam_type) {
                        $results->where('exam_id', $exam_type);
                    }
                    if ($stream_id) {
                        $results->where('results.stream_id', $stream_id);
                    }
                    if ($subject_id) {
                        $results->where('results.subject_id', $subject_id);
                    }

                    // $students = Student::where('students.class_id', $req->class_id);

                    // if ($req->stream_id) {
                    //     $students->where('students.stream_id', $req->stream_id);
                    // }

                    $students_result = [];
                    $uuids = [];

                    foreach ($results->get() as $key => $result) {

                        $students_result[$result->student_id]['score'] = $result->score;
                        $students_result[$result->student_id]['group_id'] = $result->grade_group_id;
                        $uuids[$result->student_id]['uuid'] = $result->uuid;
                    }

                    // return $students_result;

                    return DataTables::of($students)

                        ->addColumn('percentage', function ($student) use ($examInfo, $students_result) {

                            $score = $students_result[$student->id]['score'] ?? '-';

                            if ($score !== '-') {
                                $percentage = $this->global->marksToPercentage($score, $examInfo);
                                return $percentage;
                            } else {
                                return '-';
                            }
                        })

                        ->addColumn('score', function ($student) use ($students_result) {

                            return $students_result[$student->id]['score'] ?? '-';
                        })


                        ->addColumn('sn', function () {
                            return '';
                        })

                        ->addColumn('admission_no', function ($result) {

                            return $result->student_id;
                        })

                        ->editColumn('full_name', function ($result) {

                            return $result->full_name;
                        })

                        ->addColumn('grade', function ($student) use ($examInfo, $elevel, $students_result) {
                            $score = $students_result[$student->id]['score'] ?? '-';

                            if ($score !== '-') {

                                // return $student->grade_group_id;


                                return   $grade_collective = $this->global->getGrade($score, $elevel, $examInfo, $students_result[$student->id]['group_id'])['grade'];
                            } else {
                                return '-';
                            }
                        })


                        ->addColumn('remarks', function ($student) use ($examInfo, $elevel, $students_result) {

                            $score = $students_result[$student->id]['score'] ?? '-';
                            if ($score !== '-') {
                                return   $grade_collective = $this->global->getGrade($score, $elevel, $examInfo, $students_result[$student->id]['group_id'])['remarks'];
                            } else {
                                return '-';
                            }
                        })

                        ->addColumn('admission_no', function ($student) {

                            return $student->uuid;
                        })


                        ->addColumn('action', function ($student) use ($uuids) {

                            $uuid = $uuids[$student->id]['uuid'] ?? 0;
                            // return ' <span>
                            // <a href="javascript:void(0)" data-uuid="'.$uuid.'"  data-type="text" data-title="Update Marks" class="btn btn-custon-four btn-primary btn-xs editable editable-click"><i class="fa fa-edit"></i></a>
                            // </span> ';

                        })

                        ->rawColumns(['action'])
                        ->make();





                    /* REPORT TWO     --------EXAM TYPE  && NO SUBJECT */
                }


                /* 2ND REPORT     EXAM TYPE WITH NO ACTUAL SUBJECT */ elseif (!$subject_id && $exam_type) {


                    $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                        ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                        ->leftjoin('results', 'students.id', '=', 'results.student_id')
                        ->leftjoin('academic_years', 'academic_years.id', '=', 'results.academic_year_id')
                        ->leftjoin('semesters', 'academic_years.id', '=', 'semesters.academic_year_id')
                        ->leftjoin('exams', 'results.exam_id', '=', 'exams.id')
                        ->join('subjects', 'results.subject_id', 'subjects.id')
                        ->where('results.academic_year_id', $academic_year)
                        ->where('results.class_id', $class_id)
                        ->where('semesters.id', $semester)
                        ->select('results.score', 'results.class_id', 'results.grade_group_id', 'subjects.name as subject_name', 'students.id as admission_no', 'exam_id', 'results.stream_id', 'subject_id', 'results.grade_group_id')

                        ->groupBy('students.id', 'subjects.id');


                    if ($exam_type) {
                        $data->where('results.exam_id', $exam_type);
                    }
                    if ($stream_id) {
                        $data->where('results.stream_id', $stream_id);
                    }
                    if ($subject_id) {
                        $data->where('results.subject_id', $subject_id);
                    }

                    $data = $data->get();



                    $studentScores = [];

                    foreach ($subjects as $key => $subject) {

                        $code = strtolower(str_replace(' ', '_', $subject['name']));

                        $subjects_array[$code] = $code;
                    }


                    foreach ($data as $row) {

                        $subject_name =  strtolower(str_replace(' ', '_', $row->subject_name));
                        $studentScores[$row->admission_no][$subject_name] = $row->score;
                    }





                    $datatable = DataTables::of($students)

                        ->addColumn('full_name', function ($student) use ($studentScores) {

                            return  $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname;
                        })

                        ->addColumn('avg', function ($student) use ($studentScores, $subjects_array) {


                            $scores_array = [];

                            foreach ($subjects_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            return $this->global->generateAvg($scores_array);
                        })

                        ->addColumn('remarks', function ($student)  use ($studentScores, $subjects_array) {

                            $scores_array = [];

                            foreach ($subjects_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            return $this->global->generateAvg($scores_array);
                        })

                        ->addColumn('admission_no', function ($student) use ($studentScores) {

                            return  $student->uuid;
                        })

                        ->addColumn('grade', function ($student) use ($studentScores, $subjects_array, $elevel) {


                            $scores_array = [];

                            foreach ($subjects_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            $avg = $this->global->generateAvg($scores_array);
                            $examInfo = Exam::find($student->exam_id);
                            // $gradeCollection = $this->global->getGrade($avg,$elevel,$examInfo,$student->grade_group_id);
                            //  return $gradeCollection;

                        })

                        ->addColumn('sn', function ($student) use ($studentScores) {

                            return  '';
                        })

                        ->addColumn('action', function ($student) use ($exam_type, $class_id, $semester, $stream_id, $academic_year, $elevel) {


                            return '<a  target="_blank" href="' . route('reports.single.exam.pdf', [$student->uuid, $exam_type, $class_id, $semester, $academic_year, $elevel, $stream_id]) . '"  data-uuid="' . $student->uuid . '" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';
                        });


                    foreach ($subjects_array as $subject) {

                        $datatable->addColumn($subject, function ($student) use ($subject, $studentScores) {

                            return $studentScores[$student->id][$subject] ?? '-';
                        });
                    }




                    return   $datatable->rawColumns(array_merge(['student_name', 'action'], $subjects_array))
                        ->make(true);
                }


                /* REPORT --3.   SUBJECT ASSESSMENT PER TERM */ elseif ($subject_id && !$exam_type) {



                    $exams = Exam::all();

                    $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                        ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                        ->leftjoin('results', 'students.id', '=', 'results.student_id')
                        ->leftjoin('academic_years', 'academic_years.id', '=', 'results.academic_year_id')
                        ->leftjoin('semesters', 'academic_years.id', '=', 'semesters.academic_year_id')
                        ->leftjoin('exams', 'results.exam_id', '=', 'exams.id')
                        ->join('subjects', 'results.subject_id', 'subjects.id')
                        ->where('results.academic_year_id', $academic_year)
                        ->where('results.class_id', $class_id)
                        ->where('semesters.id', $semester)
                        ->select('results.score', 'results.class_id', 'subjects.name as subject_name', 'students.id as admission_no', 'exam_id', 'results.stream_id', 'subject_id')
                        ->groupBy('students.id', 'subjects.id');


                    if ($exam_type) {
                        $data->where('exam_id', $exam_type);
                    }
                    if ($stream_id) {
                        $data->where('students.stream_id', $stream_id);
                    }
                    if ($subject_id) {
                        $data->where('subject_id', $subject_id);
                    }

                    $data = $data->get();



                    $studentScores = [];

                    foreach ($exams as $key => $exam) {

                        $code = strtolower(str_replace(' ', '_', $exam['name']));

                        $exams_array[$code] = $code;
                    }

                    foreach ($data as $row) {

                        $subject_name =  strtolower(str_replace(' ', '_', $row->subject_name));
                        $studentScores[$row->admission_no][$subject_name] = $row->score;
                    }

                    $datatable = DataTables::of($students)

                        ->addColumn('full_name', function ($student) use ($studentScores) {

                            return  $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname;
                        })

                        ->addColumn('avg', function ($student) use ($studentScores, $exams_array) {


                            $scores_array = [];

                            foreach ($exams_array as $exam) {

                                $score = $studentScores[$student->id][$exam] ??  0;

                                $scores_array[] = $score;
                            }

                            return $this->global->generateAvg($scores_array);
                        })

                        ->addColumn('remarks', function ($student)  use ($studentScores, $exams_array) {

                            $scores_array = [];

                            foreach ($exams_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            return $this->global->generateAvg($scores_array);
                        })

                        ->addColumn('admission_no', function ($student) use ($studentScores) {

                            return  $student->uuid;
                        })

                        ->addColumn('grade', function ($student) use ($studentScores, $exams_array, $elevel) {


                            $scores_array = [];

                            foreach ($exams_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            $avg = $this->global->generateAvg($scores_array);
                            return $student->grade_group_id;

                            return   $gradeCollection = $this->global->getGrade($avg, $elevel, $student->grade_group_id);
                            return $gradeCollection;
                        })

                        ->addColumn('sn', function ($student) use ($studentScores) {

                            return  '';
                        })

                        ->addColumn('action', function ($student) use ($exam_type, $class_id, $semester, $stream_id, $academic_year, $elevel) {


                            return '<a  target="_blank" href="#"  data-uuid="' . $student->uuid . '" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';
                        });


                    foreach ($exams_array as $subject) {

                        $datatable->addColumn($subject, function ($student) use ($subject, $studentScores) {

                            return $studentScores[$student->id][$subject] ?? '-';
                        });
                    }




                    return   $datatable->rawColumns(array_merge(['student_name', 'action'], $exams_array))
                        ->make(true);
                }


                /* 4TH REPORT */ elseif ($class_id && !$subject_id && !$exam_type && $semester && $academic_year) {

                    $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                        ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                        ->leftjoin('results', 'students.id', '=', 'results.student_id')
                        ->leftjoin('academic_years', 'academic_years.id', '=', 'results.academic_year_id')
                        ->leftjoin('semesters', 'academic_years.id', '=', 'semesters.academic_year_id')
                        ->leftjoin('exams', 'results.exam_id', '=', 'exams.id')
                        ->join('subjects', 'results.subject_id', 'subjects.id')
                        ->where('results.academic_year_id', $academic_year)
                        ->where('results.class_id', $class_id)
                        ->where('semesters.id', $semester)
                        ->select('results.score', 'results.class_id', 'subjects.name as subject_name', 'students.id as admission_no', 'exam_id', 'results.stream_id', 'subject_id')
                        ->groupBy('students.id', 'subjects.id');


                    if ($exam_type) {
                        $data->where('exam_id', $exam_type);
                    }
                    if ($stream_id) {
                        $data->where('results.stream_id', $stream_id);
                    }
                    if ($subject_id) {
                        $data->where('subject_id', $subject_id);
                    }

                    $data = $data->get();



                    $studentScores = [];

                    foreach ($subjects as $key => $subject) {

                        $code = strtolower(str_replace(' ', '_', $subject['name']));

                        $subjects_array[$code] = $code;
                    }

                    foreach ($data as $row) {

                        $subject_name =  strtolower(str_replace(' ', '_', $row->subject_name));
                        $studentScores[$row->admission_no][$subject_name] = $row->score;
                    }

                    $datatable = DataTables::of($students)

                        ->addColumn('full_name', function ($student) use ($studentScores) {

                            return  $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname;
                        })

                        ->addColumn('avg', function ($student) use ($studentScores, $subjects_array) {


                            $scores_array = [];

                            foreach ($subjects_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            return $this->global->generateAvg($scores_array);
                        })

                        ->addColumn('remarks', function ($student)  use ($studentScores, $subjects_array) {

                            $scores_array = [];

                            foreach ($subjects_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }

                            return $this->global->generateAvg($scores_array);
                        })

                        ->addColumn('admission_no', function ($student) use ($studentScores) {

                            return  $student->uuid;
                        })

                        ->addColumn('gender', function () {
                            return '';
                        })
                        ->addColumn('grade', function ($student) use ($studentScores, $subjects_array, $elevel) {


                            $scores_array = [];

                            foreach ($subjects_array as $subject) {

                                $score = $studentScores[$student->id][$subject] ??  0;

                                $scores_array[] = $score;
                            }


                            $avg = $this->global->generateAvg($scores_array);

                            $gradeCollection = $this->global->getGrade($avg, $elevel);
                            return $gradeCollection;
                        })

                        ->addColumn('sn', function ($student) use ($studentScores) {

                            return  '';
                        })

                        ->addColumn('action', function ($student) use ($exam_type, $class_id, $semester, $stream_id, $academic_year, $elevel) {


                            return '<a  target="_blank" href="#"  data-uuid="' . $student->uuid . '" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';
                        });


                    foreach ($subjects_array as $subject) {

                        $datatable->addColumn($subject, function ($student) use ($subject, $studentScores) {

                            return $studentScores[$student->id][$subject] ?? '-';
                        });
                    }




                    return   $datatable->rawColumns(array_merge(['student_name', 'action'], $subjects_array))
                        ->make(true);
                }
            } elseif (count($exam_type) > 1) {
                /* tunaanzia apa */

                $datatable = DataTables::of($students)

                    ->addColumn('full_name', function ($student) {

                        return  $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname;
                    })

                    ->addColumn('admission_no', function ($student) {

                        return  $student->uuid;
                    })

                    ->addColumn('gender', function () {
                        return '';
                    })


                    ->addColumn('sn', function ($student) {

                        return  '';
                    })

                    ->addColumn('action', function ($student) use ($exam_type, $class_id, $semester, $stream_id, $academic_year, $elevel) {


                        return '<a  target="_blank" href="' . route(
                            'reports.multiple.exam.pdf',
                            [
                                $student->uuid,
                                http_build_query(['exam_type' => $exam_type]),
                                $class_id,
                                $semester,
                                $academic_year,
                                $elevel,
                                $stream_id
                            ]
                        ) . '"  data-uuid="' . $student->uuid . '" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';
                    });

                return   $datatable->rawColumns(array_merge(['student_name', 'action']))
                    ->make(true);

                /* we end here */
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function singeExamReportPdf(
        $student_uuid,
        $exam_type,
        $class_id,
        $semester,
        $academic_year,
        $elevel,
        $stream_id = null

    ) {


        $data['subjects'] = Subject::join('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')
            ->select('subjects.name', 'subjects.id')->where('education_level_id', $elevel)->get();

        $results = Result::join('students', 'students.id', '=', 'results.student_id')
            ->where('results.exam_id', $exam_type)
            ->where('results.class_id', $class_id)
            ->where('results.semester_id', $semester)
            ->where('students.uuid', $student_uuid)
            ->where('results.academic_year_id', $academic_year);

        if ($stream_id) {
            $results->where('results.stream_id', $stream_id);
        }

        $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

        $data['examInfo'] = $examInfo;
        $data['results'] = $results->get();
        $data['elevel'] = $elevel;

        $data['studentInfo'] = Student::leftjoin('school_classes', 'school_classes.id', '=', 'students.class_id')
            ->leftjoin('streams', 'streams.id', '=', 'students.stream_id')
            ->select('students.firstname', 'students.middlename', 'students.lastname', 'streams.name as stream_name', 'school_classes.name as class_name')
            ->where('students.uuid', $student_uuid)
            ->first();


        $pdf = PDF::loadView('results.reports.printouts.single_exam_type_report', $data);

        // return view('results.reports.printouts.single_exam_type_report')->with($data);
        return $pdf->stream('students.pdf');
    }




    public function singleSubjectExamTypeChecked(
        $academic_year,
        $class_id,
        $semester,
        $subject_id,
        $exam_type,
        $elevel,
        $stream_id = null
    ) {


        $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

        $students = SchoolClass::find($class_id)->students;

        $data['class'] = SchoolClass::find($class_id);
        $data['stream'] = '';
        $data['elevel'] = $elevel;

        $students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
            ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
            ->select('students.id', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
            ->where('students.class_id', $class_id);

        if ($stream_id) {

            $students->where('students.stream_id', $stream_id);
        }

        $data['subject'] = Subject::find($subject_id);

        $results = Student::leftjoin('results', 'students.id', 'results.student_id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select('results.score', 'results.uuid', 'results.student_id', 'results.grade_group_id')
            ->where('results.class_id', $class_id)
            ->where('results.semester_id', $semester)
            ->where('results.academic_year_id', $academic_year);

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

        $students_result = [];
        $uuids = [];

        foreach ($results->get() as $key => $result) {

            $students_result[$result->student_id]['score'] = $result->score;
            $students_result[$result->student_id]['grade_group_id'] = $result->grade_group_id;
            $uuids[$result->student_id]['uuid'] = $result->uuid;
        }

        $data['students'] = $students->get();
        $data['examInfo'] = $examInfo;
        $data['students_result'] = $students_result;

        $pdf = PDF::loadView('results.reports.printouts.single_subject_exam_type_checked', $data);
        return $pdf->stream('single_exam_subject.pdf');
    }


    public function multipleExamReportPdf(
        $student_uuid,
        $exam_type,
        $class_id,
        $semester,
        $academic_year,
        $elevel,
        $stream_id = null

    ) {

        $examtypeArray = [];


        parse_str($exam_type, $examtypeArray);


        $data['subjects'] = Subject::join('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')
            ->select('subjects.name', 'subjects.id')->where('education_level_id', $elevel)->get();


        $results = Result::join('students', 'students.id', '=', 'results.student_id')
            ->whereIn('results.exam_id', $examtypeArray['exam_type'])
            ->where('results.class_id', $class_id)
            ->where('results.semester_id', $semester)

            ->where('students.uuid', $student_uuid)
            ->where('results.academic_year_id', $academic_year);



        if ($stream_id) {
            $results->where('results.stream_id', $stream_id);
        }

        $exam_type ?  $examInfo = Exam::whereIn('exams.id', $examtypeArray['exam_type'])->where('exams.isCommutative', 1)->get() : $examInfo = '';

        $data['examInfo'] = $examInfo;

        $data['results'] = $results->get();
        $data['elevel'] = $elevel;

        $data['studentInfo'] = Student::leftjoin('school_classes', 'school_classes.id', '=', 'students.class_id')
            ->leftjoin('streams', 'streams.id', '=', 'students.stream_id')
            ->select('students.firstname', 'students.middlename', 'students.lastname', 'streams.name as stream_name', 'school_classes.name as class_name')
            ->where('students.uuid', $student_uuid)
            ->first();


        $pdf = PDF::loadView('results.reports.printouts.multiple_exam_types_report', $data);

        // return view('results.reports.printouts.single_exam_type_report')->with($data);
        return $pdf->stream('students.pdf');
    }



    /* HERE WE COME AMIGO */


    public function generateExamReport(Request $req)
    {
        //  dd($req);
        // return $req;

        try {


            DB::beginTransaction();
            $class_id = $req->class_id;
            $stream = $req->stream_id;
            $metadata = decrypt($req->metadata);
            $students = Student::where(['class_id' => $class_id, 'stream_id' => $stream])->get();

            $ct_comments = $req->ct_comment;




            //    return    $exam_type = $req->exam_type;

            //        return gettype($exam_type);


            $subject = $req->subject;
            $exam_type = $req->exam_type;

            $generated_report = GeneratedExamReport::create([
                'uuid' => generateUuid(),
                'class_id' => $req->class_id,
                'stream_id' => $req->stream_id,
                'generated_by' => auth()->user()->id,
                'exam_report_id' => $req->report_name,
                'academic_year_id' => $req->academic_year,
                'term_id' => $req->term_id,
                'have_ca' => $req->ca,
                'have_es' => $req->es,
                'exam_type_combination' => json_encode($exam_type),
                'for_my_grade' => $req->for_my_grade,
                'subject_type_combination' => $subject,
                'escalation_level_id' => 1
            ]);

            if ($generated_report) {

                foreach ($students as $key => $student) {

                    $student_id = $student->id;
                    // dd($ct_comments);

                    if (isset($metadata[$student_id])) {

                        $studentData = $metadata[$student_id];

                        $student_gnr =  StudentResultReport::create([

                            'generated_exam_report_id' => $generated_report->id,
                            'uuid' => generateUuid(),
                            'student_id' => $studentData['admission_no'],
                            'user_id' => auth()->user()->id,
                            'metadata' => json_encode($studentData),
                            'full_name' => $studentData['full_name'],
                            'class_position' => '',
                            'stream_position' => '',
                            'division' => $studentData['CODE'],
                            'points' => $studentData['POINTS'],
                            'grade' => isset($studentData['GRADE']) ?  $studentData['GRADE'] : '',
                            'avg' => $studentData['AVG'],
                            'remarks' => $studentData['REMARKS'],

                        ]);



                        // foreach($ct_comments as $ct_student_id =>$comment){
                        //     // if($ct_comments[$student_id] != 'Add Comment....')
                        //     if ($comment  != 'Add Comment....') {
                        //         // dd($student_id);
                        //         Comment::create(
                        //             [
                        //                 'student_result_report_id' => $student_gnr->id,
                        //                 // 'predefined_comment_id' => $ct_comments[$student_id],
                        //                 'predefined_comment_id' => $comment,
                        //                 'user_id' => auth()->user()->id,
                        //                 'student_id'=>$ct_student_id

                        //             ]

                        //         );
                        //     }

                        // }

                        foreach ($ct_comments as $ct_student_id => $comment) {
                            if ($student_id == $ct_student_id && $comment  != 'Add Comment....') {
                                Comment::create([
                                    'student_result_report_id' => $student_gnr->id,
                                    'predefined_comment_id' => $comment,
                                    'user_id' => auth()->user()->id,
                                    'student_id' => $ct_student_id
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            $data = ['status' => 'done', 'title' => 'success', 'msg' => 'Report Generated'];
            return response($data);

            // return $req->all();
            // return response($generated_report);

        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function generatedExamReportsIndex()
    {

        $data['activeTab'] = 'generatedReportsTab';
        return view('results.reports.generated.index')->with($data);
    }

    public function generatedExamReportsDatatable(Request $request)
    {
        try {
            $generated_reports = GeneratedExamReport::select(
                'academic_years.name as acdmc_name',
                'school_classes.name as class_name',
                'streams.name as stream_name',
                'semesters.name as semester_name',
                'exam_reports.name as report_name',
                'generated_exam_reports.escalation_level_id',
                'generated_exam_reports.academic_year_id',
                'escalation_levels.name as e_name',
                'generated_exam_reports.uuid as report_uuid'
            )
                ->join('academic_years', 'academic_years.id', '=', 'generated_exam_reports.academic_year_id')
                ->join('school_classes', 'school_classes.id', '=', 'generated_exam_reports.class_id')
                ->join('streams', 'streams.id', '=', 'generated_exam_reports.stream_id')
                ->join('semesters', 'generated_exam_reports.term_id', '=', 'semesters.id')
                ->join('exam_reports', 'generated_exam_reports.exam_report_id', '=', 'exam_reports.id')
                ->join('escalation_levels', 'escalation_levels.id', '=', 'generated_exam_reports.escalation_level_id');

            if (auth()->user()->hasRole('Academic')) {

                $generated_reports->whereIn('generated_exam_reports.escalation_level_id', [2, 3]);
            }

            if (auth()->user()->hasRole('Head Master')) {
                $generated_reports->whereIn('generated_exam_reports.escalation_level_id', [3]);
            }

            // my filter for teachers
            $user = auth()->user();
            if ($user->hasRole('Teacher')) {

                $generated_reports->where('generated_exam_reports.generated_by', $user->id);
            }


            $generated_reports->orderBy('generated_exam_reports.id', 'DESC');
            $search = $request->get('search');

            if (!empty($search)) {

                //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
                //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
                //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
                //    }

                // $invoices = $invoices->groupBy('invoices.id');
            }

            return DataTables::of($generated_reports)

                ->editColumn('academic_year_id', function ($report) {

                    return $report->acdmc_name;
                })

                ->editColumn('term_id', function ($report) {

                    return $report->semester_name;
                })

                ->editColumn('class_id', function ($report) {

                    return $report->class_name;
                })

                ->editColumn('stream_id', function ($report) {

                    return $report->stream_name;
                })

                ->editColumn('exam_report_id', function ($report) {


                    return $report->report_name;
                })

                ->editColumn('escalation_level_id', function ($report) {
                    if ($report->e_name == 'Pending') {
                        $badge = '<span class="badge badge-warning">Pending</span>';
                    } elseif ($report->e_name == 'Published') {
                        $badge = '<span class="badge badge-success">Published</span>';
                    } else {
                        $badge = '<span class="badge badge-info"> ' . $report->e_name . ' </span>';
                    }
                    return $badge;
                })

                ->editColumn('created_by', function ($report) {

                    return 'admin';
                })

                ->addColumn('action', function ($report) {
                    if ($report->e_name == 'Published') {
                        return '<span> <a  data-uuid="' . $report->uuid . '" href="' . route('results.reports.generated.reports.view.indrive', $report->report_uuid) . '" type="button" class="btn  btn-outline-success btn-sm preview"><i class="fa fa-eye"></i></a>

                        <a target="_blank"  data-uuid="' . $report->uuid . '" href="' . route('results.reports.generated.reports.view.indrive.print', $report->report_uuid) . '" type="button" class="btn  btn-outline-info btn-sm print"><i class="fa fa-print"></i></a>

                 </span>';
                    } else {
                        return '<span> <a  data-uuid="' . $report->uuid . '" href="' . route('results.reports.generated.reports.view.indrive', $report->report_uuid) . '" type="button" class="btn  btn-outline-success btn-sm preview"><i class="fa fa-eye"></i></a>
                 </span>';
                    }
                })

                ->rawColumns(['action', 'escalation_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function oneLevelUp(Request $req)
    {
        try {

            DB::beginTransaction();
            // return $req->all();
            $signature = $req->signature;
            // EscalationLevel::create(['uuid'=>generateUuid(),'name'=>'Published']);
            $uuid = $req->uuid;
            $report = GeneratedExamReport::where('uuid', $uuid)->first();
            $new_level =  $report->escalation_level_id + 1;
            $gn = GeneratedExamReport::where('uuid', $uuid)->first();
            $attempt = GeneratedExamReport::where('uuid', $uuid)->update(['escalation_level_id' => $new_level]);
            DB::commit();

            if ($attempt) {

                $data = ['msg' => 'Report Escalated', 'title' => 'success'];

                if (auth()->user()->hasRole('Head Master')) {

                    $gn->update(['is_published' => 1, 'include_signature' => $signature]);

                    $data = ['msg' => 'Report Saved & Published', 'title' => 'success'];
                }

                return response($data);
            }
            $data = ['msg' => 'OOPS... Something Went Wrong...', 'title' => 'info'];
            return response()->json($data);
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function generatedExamReportViewIndrive($uuid)
    {
        $data['generated_exam_report'] = $generated_exam_report = GeneratedExamReport::where('uuid', $uuid)->first();
        $exam_type_combination = $generated_exam_report->exam_type_combination;
        $class_id = $generated_exam_report->class_id;
        $stream_id = $generated_exam_report->stream_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $data['predefined_comments'] = PredefinedComment::all();
        $grade_group = $generated_exam_report->for_my_grade;
        $data['uuid'] = $uuid;

        if ($exam_type_combination) {

            $exam_type = decrypt($exam_type_combination);
            $count  = count($exam_type);
            $generated_exam_report->subject_type_combination;


            $subjects = ClastreamSubject::join('subjects', 'subjects.id', '=', 'clastream_subjects.subject_id')->join('school_classes', 'school_classes.id', '=', 'clastream_subjects.class_id')
                ->join('streams', 'streams.id', '=', 'clastream_subjects.stream_id')
                ->select('streams.name as stream_name', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
                ->where(['clastream_subjects.class_id' => $class_id, 'clastream_subjects.stream_id' => $stream_id])->get();


            $load_students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
                ->select('students.id', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
                ->where('students.class_id', $class_id);

            if ($stream_id) {

                $load_students->where('students.stream_id', $stream_id);
            }

            $data['students'] = $load_students->get();
            $data['subjects'] = $subjects;

            $data['matokeo'] = $matokeo = StudentResultReport::select(
                'student_result_reports.student_id',
                'student_result_reports.*',
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'HM' THEN comments.predefined_comment_id END) as hm_predefined_comment_ids"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'CT' THEN comments.predefined_comment_id END) as ct_predefined_comment_ids"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'HM' THEN predefined_comments.comment END) as hm_comments"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'CT' THEN predefined_comments.comment END) as ct_comments")
            )
                ->leftJoin('comments', 'comments.student_result_report_id', '=', 'student_result_reports.id')
                ->leftJoin('predefined_comments', 'comments.predefined_comment_id', '=', 'predefined_comments.id')
                ->where('generated_exam_report_id', $generated_exam_report->id)
                ->groupBy('student_result_reports.student_id')
                ->get();

            $data['processedData'] = $this->processDataIndrive($matokeo);

            $data['processGradeAverages'] = $this->processGradeAveragesIndrive($matokeo);

            $data['divisionCount'] = collect($matokeo)
                ->pluck('division')
                ->countBy()
                ->mapWithKeys(function ($count, $division) {
                    return ["Division $division" => $count];
                })
                ->toArray();

            $data['gradeLabels'] = Grade::where(['group_id' => $grade_group, 'education_level_id' => $elevel])->pluck('name');

            $data['trigger_hm'] = 0;

            //    return $mm =  $matokeo->student_id;

            foreach ($matokeo as $key => $tokeo) {

                $studentId = $tokeo->student_id;
                $studentuuId = Student::where('id', $studentId)->first()->uuid;

                $genr_exam_repo_uuid = GeneratedExamReport::where('id', $tokeo->generated_exam_report_id)->first()->uuid;

                $checkpoint =  count(Comment::where('student_result_report_id', $tokeo->id)->whereIn('type', ['HM'])->get());

                if ($checkpoint) {
                    $data['trigger_hm'] = 1;
                    break;
                }

                $printRoute = route('results.reports.generated.reports.view.indrive.print.single', [

                    'generated_exam_report_uuid' => $genr_exam_repo_uuid,
                    'student_uuid' => $studentuuId

                ]);

                $printButton = '<span style="display:center; justify-content:center; align-items:center">
                    <a target="_blank" href="' . $printRoute . '" type="button" class="btn btn-outline-info btn-sm print">
                        <i class="fa fa-print"></i>
                    </a>
                </span>';


                $data['printButtons'][$studentId] = $printButton;
            }


            if ($count == 1) {

                $exam_type = decrypt($exam_type_combination)[0];
                $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';
                $data['examInfo'] = $examInfo;
                $data['predefined_comments'] = PredefinedComment::all();
                $data['activeTab'] = 'previewTab';

                return view('results.reports.generated.indrive_single_exam_all_subjects')->with($data);
            } else if ($count > 1) {


                $exam_type = decrypt($exam_type_combination);
                $data['predefined_comments'] = PredefinedComment::all();
                $data['activeTab'] = 'previewTab';

                $colspan = 0;
                $sbjct_columns = [];

                $exam_type_columns = array();
                $data['examodel'] = Exam::class;
                $data['exam_type'] = $exam_type;

                // return $subjects;

                foreach ($subjects as $key => $subject) {
                    $code  =   strtolower(str_replace(' ', '_', $subject->subject_name));
                    $subjct_span = 0;
                    foreach ($exam_type as $key => $exam) {
                        $exam_model = Exam::find($exam);
                        $subjct_span += 1;
                        $exam_type_columns[] = [
                            'subject_id' => $subject->sbjct_id,
                            'exam' => $exam_model,
                        ];
                    }
                    $subjct_span += 3;

                    $sbjct_columns[] = [
                        'data' => $subject->sbjct_code,
                        'name' => $code,
                        'subjct_span' => $subjct_span,
                        'subjct_uuid' => $subject->uuid,
                        'subject_id' => $subject->sbjct_id,
                    ];


                    $colspan += 1;
                }

                // return $sbjct_columns;

                /* poooof voala */

                $data['sbjct_columns'] = $sbjct_columns;
                $data['exam_type_columns'] = $exam_type_columns;

                $data['subjects'] = $subjects;
                $data['colspan'] = $colspan  * $subjct_span;


                // foreach ($matokeo as $key=> $tokeo ){

                //     $metadata = json_decode($tokeo->metadata);
                //     $results = $metadata->results;


                //    if($generated_exam_report->is_published){

                //    $student_id_tk = $tokeo->student_id;

                //    }

                //    for ($i=0; $i<count($sbjct_columns);  $i++){

                //     foreach ($exam_type as $key => $column){

                //         $subject_id = $sbjct_columns[$i]['subject_id'];


                //         $score = '-';
                //         if (isset($results->$subject_id)) {


                //             $check = $results->$subject_id;

                //             if (isset($check->$column)) {
                //               $score =  $check->$column;
                //         }
                //         }

                //     }

                //     $avg =   $check->AVG;

                //    }

                // //    $metadata->avg;


                // }

                // return true;
                // return $data;


                $data['printButtons'][$studentId] = $printButton;

                return view('results.reports.generated.indrive_multiple_exam_all_subjects')->with($data);
                /* end */
            }
        }
    }


    // printing the above generated report

    public function generatedExamReportViewIndrivePrint($uuid)
    {

        $data['generated_exam_report'] = $generated_exam_report = GeneratedExamReport::where('uuid', $uuid)->first();
        $exam_type_combination = $generated_exam_report->exam_type_combination;
        $class_id = $generated_exam_report->class_id;
        $stream_id = $generated_exam_report->stream_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $data['predefined_comments'] = PredefinedComment::all();
        $grade_group = $generated_exam_report->for_my_grade;
        $data['uuid'] = $uuid;

        if ($exam_type_combination) {

            $exam_type = decrypt($exam_type_combination);
            $count  = count($exam_type);
            $generated_exam_report->subject_type_combination;


            $subjects = ClastreamSubject::join('subjects', 'subjects.id', '=', 'clastream_subjects.subject_id')->join('school_classes', 'school_classes.id', '=', 'clastream_subjects.class_id')
                ->join('streams', 'streams.id', '=', 'clastream_subjects.stream_id')
                ->select('streams.name as stream_name', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
                ->where(['clastream_subjects.class_id' => $class_id, 'clastream_subjects.stream_id' => $stream_id])->get();


            $load_students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
                ->select('students.id', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
                ->where('students.class_id', $class_id);

            if ($stream_id) {

                $load_students->where('students.stream_id', $stream_id);
            }

            $data['students'] = $load_students->get();
            $data['subjects'] = $subjects;

            $data['matokeo'] = $matokeo = StudentResultReport::select(
                'student_result_reports.student_id',
                'student_result_reports.*',
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'HM' THEN comments.predefined_comment_id END) as hm_predefined_comment_ids"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'CT' THEN comments.predefined_comment_id END) as ct_predefined_comment_ids"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'HM' THEN predefined_comments.comment END) as hm_comments"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN comments.type = 'CT' THEN predefined_comments.comment END) as ct_comments")
            )
                ->leftJoin('comments', 'comments.student_result_report_id', '=', 'student_result_reports.id')
                ->leftJoin('predefined_comments', 'comments.predefined_comment_id', '=', 'predefined_comments.id')
                ->where('generated_exam_report_id', $generated_exam_report->id)
                ->groupBy('student_result_reports.student_id')
                ->get();

            $data['processedData'] = $this->processDataIndrive($matokeo);

            $data['processGradeAverages'] = $this->processGradeAveragesIndrive($matokeo);

            $data['divisionCount'] = collect($matokeo)
                ->pluck('division')
                ->countBy()
                ->mapWithKeys(function ($count, $division) {
                    return ["Division $division" => $count];
                })
                ->toArray();

            $data['gradeLabels'] = Grade::where(['group_id' => $grade_group, 'education_level_id' => $elevel])->pluck('name');

            $data['trigger_hm'] = 0;

            foreach ($matokeo as $key => $tokeo) {
                $checkpoint =  count(Comment::where('student_result_report_id', $tokeo->id)->whereIn('type', ['HM'])->get());

                if ($checkpoint) {
                    $data['trigger_hm'] = 1;
                    break;
                }
            }

            if ($count == 1) {

                $exam_type = decrypt($exam_type_combination)[0];
                $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';
                $data['examInfo'] = $examInfo;
                $data['predefined_comments'] = PredefinedComment::all();
                $data['activeTab'] = 'previewTab';

                return view('results.reports.generated.indrive_single_exam_all_subjects')->with($data);
            } else if ($count > 1) {


                $exam_type = decrypt($exam_type_combination);
                $data['predefined_comments'] = PredefinedComment::all();
                $data['activeTab'] = 'previewTab';

                $colspan = 0;
                $sbjct_columns = [];

                $exam_type_columns = array();
                $data['examodel'] = Exam::class;
                $data['exam_type'] = $exam_type;

                // return $subjects;

                foreach ($subjects as $key => $subject) {
                    $code  =   strtolower(str_replace(' ', '_', $subject->subject_name));
                    $subjct_span = 0;
                    foreach ($exam_type as $key => $exam) {
                        $exam_model = Exam::find($exam);
                        $subjct_span += 1;
                        $exam_type_columns[] = [
                            'subject_id' => $subject->sbjct_id,
                            'exam' => $exam_model,
                        ];
                    }
                    $subjct_span += 1;

                    $sbjct_columns[] = [
                        'data' => $subject->sbjct_code,
                        'name' => $code,
                        'subjct_span' => $subjct_span,
                        'subjct_uuid' => $subject->uuid,
                        'subject_id' => $subject->sbjct_id,
                    ];


                    $colspan += 1;
                }

                // return $sbjct_columns;

                /* poooof voala */

                $data['sbjct_columns'] = $sbjct_columns;
                $data['exam_type_columns'] = $exam_type_columns;

                $data['subjects'] = $subjects;
                $data['colspan'] = $colspan  * $subjct_span;

                $examTypeNames = Exam::whereIn('id', $data['exam_type'])->pluck('name')->toArray();

                $data['exam_name_combine'] = $combinedNames = implode(', ', $examTypeNames);

                $data['class_name'] = SchoolClass::where('id', $data['generated_exam_report']->class_id)->first()->name;
                $data['stream_name'] = Stream::where('id', $data['generated_exam_report']->stream_id)->first()->name;
                $data['year'] = AcademicYear::where('id', $data['generated_exam_report']->academic_year_id)->first()->name;
                $data['semester'] = Semester::where('id', $data['generated_exam_report']->term_id)->first()->name;
                $logo = SchoolProfile::get();

                $data['school_logo'] = asset('storage/' . $logo[0]['school_logo']);

                // return $data;

                // foreach ($matokeo as $key=> $tokeo ){

                //     $metadata = json_decode($tokeo->metadata);
                //     $results = $metadata->results;


                //    if($generated_exam_report->is_published){

                //    $student_id_tk = $tokeo->student_id;

                //    }

                //    for ($i=0; $i<count($sbjct_columns);  $i++){

                //     foreach ($exam_type as $key => $column){

                //         $subject_id = $sbjct_columns[$i]['subject_id'];


                //         $score = '-';
                //         if (isset($results->$subject_id)) {


                //             $check = $results->$subject_id;

                //             if (isset($check->$column)) {
                //               $score =  $check->$column;
                //         }
                //         }

                //     }

                //     $avg =   $check->AVG;

                //    }

                // //    $metadata->avg;


                // }

                // return true;

                //   view('results.reports.generated.indrive_multiple_exam_all_subjects_print')->with($data);
                $pdf = PDF::loadView('results.reports.generated.indrive_multiple_exam_all_subjects_print', $data);

                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('multiple-exam-report.pdf');
            }
        }
    }

    public function generatedExamReportViewIndrivePrintSingle($generated_exam_report_uuid, $student_uuid)
    {
        //  return $student_uuid;
        $id = Student::where('uuid', $student_uuid)->first()->id;

        $collective = $col = StudentResultReport::where('student_id', $id)
            ->join('generated_exam_reports', 'student_result_reports.generated_exam_report_id', '=', 'generated_exam_reports.id')
            ->select('student_result_reports.*', 'generated_exam_reports.*')
            ->whereNull('deleted_at')
            ->where('generated_exam_reports.uuid', $generated_exam_report_uuid)
            ->get();

        $generated_exam_report_id = $collective[0]->generated_exam_report_id;

        $generated_exam_report = GeneratedExamReport::find($generated_exam_report_id);
        $stream_id = $generated_exam_report->stream_id;
        $class_id = $generated_exam_report->class_id;
        $term_id = $generated_exam_report->term_id;
        $year = $generated_exam_report->academic_year_id;

        $exam_type_combination = decrypt($generated_exam_report->exam_type_combination);
        $exam_report_id = $generated_exam_report->exam_report_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $student_id = $student = $collective[0]->student_id;

        $reports = [];

        $data = array();

        $data['semester'] = Semester::find($term_id);
        $data['exam_report'] = ExamReport::find($exam_report_id);
        $data['school_class'] = SchoolClass::find($class_id);
        $data['stream'] = Stream::find($stream_id);

        $subjects = StudentSubjectsAssignment::join('subjects', 'subjects.id', '=', 'student_subjects_assignments.subject_id')->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
            ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
            ->select('streams.name as stream_name', 'student_subjects_assignments.student_id', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
            ->where(['student_subjects_assignments.class_id' => $class_id, 'student_subjects_assignments.stream_id' => $stream_id])
            ->groupBy('subjects.id');


        $data['year'] = AcademicYear::find($year);
        $data['is_signature'] = $generated_exam_report->include_signature;

        if (count($exam_type_combination) == 1) {
            $exam_type = $exam_type_combination[0];
            $data['exam'] = Exam::find($exam_type);
            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            // return $student_id;
            $data['subjects'] =  $subjects->where('student_subjects_assignments.student_id', $student)->get();

            foreach ($collective as $key => $col) {

                if ($col->student_id == $student) {
                    $data['results'] = $col;
                    $data['metadata'] =  json_decode($col->metadata);

                    // $pdf = FacadePdf::loadView('results.reports.printouts.single_exam_type_report',  $data);
                    // $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                    // $pdf->save($pdfPath);
                    // $pdfMerger->addPDF($pdfPath, 'all');
                    // $reports[] = $pdfPath;

                    $pdf = PDF::loadView('results.reports.printouts.single_exam_type_report', $data);

                    $pdfFileName = 'single_student_exam_report_' . $student . '.pdf';
                    $pdfFilePath = $directoryPath . '/' . $pdfFileName;
                    $pdf->save($pdfFilePath);

                    // Provide a download link to the user.
                    $downloadLink = asset('reports/temp/' . $pdfFileName);
                    return redirect()->to($downloadLink);
                }
            }
        } elseif (count($exam_type_combination) > 1) {
            // return "tunapita hapa";

            $sbjct_columns = [];

            $colspan = 0;

            $exam_type_columns = array();
            $data['examodel'] = Exam::class;
            $data['exam_type'] = $exam_type = $exam_type_combination;
            $data['exams'] = $exams = Exam::whereIn('exams.id', $exam_type)->get();
            $span = 0;

            foreach ($exams as $key => $exam) {

                if ($exam->is_dp) {

                    ++$span;
                }
            }

            $data['span'] = $span;
            $subjct_span = 1;

            foreach ($subjects->get() as $key => $subject) {

                // return $subject;
                $code  =   strtolower(str_replace(' ', '_', $subject->subject_name));
                $subjct_span = 0;
                foreach ($exam_type as $key => $exam) {
                    $exam_model = Exam::find($exam);
                    $subjct_span += 1;
                    $exam_type_columns[] = [
                        'subject_id' => $subject->sbjct_id,
                        'exam' => $exam_model,
                    ];
                }
                $subjct_span += 3;
                $sbjct_columns[] = [
                    'data' => $subject->sbjct_code,
                    'name' => $code,
                    'subjct_span' => $subjct_span,
                    'subjct_uuid' => $subject->uuid,
                    'subject_id' => $subject->sbjct_id,
                ];
                $colspan += 1;
            }

            /* poooof voala */

            $data['sbjct_columns'] = $sbjct_columns;
            $data['exam_type_columns'] = $exam_type_columns;

            $data['subjects'] = $subjects->get();
            $data['colspan'] = $colspan  * $subjct_span;
            $data['student_id'] = $student;;

            /* end */

            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }


            foreach ($collective as $key => $col) {

                if ($col->student_id == $student) {

                    $assignd_subjects = $subjects->where('student_subjects_assignments.student_id', $student)->get();
                    $data['rst'][$student] = [];
                    $data['rst'][$student]['subjects'] = [];

                    $data['rst'][$student]['subjects'] = StudentSubjectsAssignment::join('subjects', 'subjects.id', '=', 'student_subjects_assignments.subject_id')->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
                        ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
                        ->select('streams.name as stream_name', 'student_subjects_assignments.student_id', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
                        ->where(['student_subjects_assignments.class_id' => $class_id, 'student_subjects_assignments.stream_id' => $stream_id])
                        ->groupBy('subjects.id')->where('student_subjects_assignments.student_id', $student)->get();

                    $data['rst'][$student]['results'] = $init = $col;

                    $generated_report_id =  StudentResultReport::where('generated_exam_report_id', $init->generated_exam_report_id)->first()->id;

                    $data['rst'][$student]['profile_pic'] = Student::find($col->student_id)->profile_pic;
                    $data['rst'][$student]['metadata'] = $metadata = json_decode($col->metadata);

                    // $cst_model = ClassTeacher::where(['stream_id'=>Student::find($col->student_id)->stream_id, 'class_id'=>Student::find($col->student_id)->class_id])->first();
                    $cst_model = ClassTeacher::where(['stream_id' => Student::find($col->student_id)->stream_id, 'class_id' => Student::find($col->student_id)->class_id, 'level_flag' => 1])->first();
                    $data['class_teacher'] = $user = User::where('id', $cst_model->teacher_id)->first();

                    $cmtmodel = Comment::where('student_result_report_id', $generated_report_id)
                        ->where('student_id', $metadata->admission_no)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($cmtmodel) {
                        $data['comment'] = PredefinedComment::find($cmtmodel->predefined_comment_id)->comment;
                    } else {
                        $data['comment'] = "no comment";
                    }

                    $pdf = PDF::loadView('results.reports.printouts.multiple_exam_types_report', $data);

                    $pdfFileName = 'single_student_exam_report_' . $student . '.pdf';
                    $pdfFilePath = $directoryPath . '/' . $pdfFileName;
                    $pdf->save($pdfFilePath);

                    // Provide a download link to the user.
                    $downloadLink = asset('reports/temp/' . $pdfFileName);
                    return redirect()->to($downloadLink);
                }
            }

            // return $pdf->stream('single_student_exam_report.pdf');
        }
    }



    /* COMMENT HM UPDATE */

    public function hmCommentUpdate(Request $req)
    {

        try {


            $gnr = StudentResultReport::where('uuid', $req->report_uuid)->first();
            if ($req->selected_value) {
                DB::beginTransaction();

                $comment = Comment::updateOrCreate(
                    [
                        'student_result_report_id' => $gnr->id,
                        'type' => $req->type,
                    ],
                    [
                        'predefined_comment_id' => $req->selected_value

                    ]
                );

                DB::commit();
                if ($comment) {
                    $data = ['msg' => 'comment added', 'title' => 'success', 'state' => 'done'];
                    return response()->json($data);
                }
                $data = ['msg' => 'Fail', 'title' => 'Ooops... Something wrong'];
                return response()->json($data);
            }
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }



    /* END */




















    //   public function singleSubjectSingleExamType(){

    //     $class_id = $req->class_id;
    //     $exam_type = $req->exam_type;
    //     $semester = $req->semester;
    //     $stream_id = $req->stream_id;
    //     $subject_id = $req->subject_id;
    //     $academic_year = $req->acdmcyear;
    //     $subjects = $req->subjects;
    //     $elevel = $req->elevel;





    //     $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

    //     $students = SchoolClass::find($class_id)->students;


    //     $students = Student::join('school_classes','students.class_id','=','school_classes.id')
    //     ->leftjoin('streams','students.stream_id','=','streams.id')->orderBy('students.id')
    //     ->select('students.id','students.firstname','students.middlename','students.uuid','students.lastname')
    //     ->where('students.class_id',$class_id);

    //     if ($stream_id) {

    //         $students->where('students.stream_id', $stream_id);

    //     }




    //   $results = Student::leftjoin('results','students.id','results.student_id')
    //   ->join('subjects', 'results.subject_id', '=', 'subjects.id')
    //   ->select('results.score','results.uuid','results.student_id')
    //   ->where('results.class_id', $class_id)
    //   ->where('results.semester_id', $semester)
    //   ->where('results.academic_year_id', $academic_year);

    //   if ($exam_type) {
    //       $results->where('exam_id', $exam_type);
    //   }
    //   if ($stream_id) {
    //       $results->where('stream_id', $stream_id);
    //   }
    //   if ($subject_id) {
    //       $results->where('subject_id', $subject_id);
    //   }

    //   $students = Student::where('students.class_id', $req->class_id);

    //   if ($req->stream_id) {
    //       $students->where('students.stream_id', $req->stream_id);
    //   }

    //   $students_result = [];
    //   $uuids = [];

    //   foreach ($results->get() as $key => $result) {

    //       $students_result[$result->student_id]['score'] = $result->score;
    //       $uuids[$result->student_id]['uuid'] = $result->uuid;
    //   }

    //   return DataTables::of($students)

    //   ->addColumn('percentage',function($student) use($examInfo,$students_result){

    //       $score = $students_result[$student->id]['score'] ?? '-';

    //       if ($score !== '-') {
    //           $percentage = $this->global->marksToPercentage($score, $examInfo->total_marks);
    //           return $percentage;
    //       } else {
    //           return '-';
    //       }


    //   })

    //   ->addColumn('score', function($student) use ($students_result){

    //       return $students_result[$student->id]['score'] ?? '-';


    //   })


    //   ->addColumn('sn', function(){
    //       return '';
    //   })

    //   ->addColumn('admission_no', function($result){

    //       return $result->student_id;


    //   })

    //   ->editColumn('full_name', function($result){

    //       return $result->full_name;

    //   })

    //   ->addColumn('grade',function($student) use($examInfo,$elevel,$students_result){
    //       $score = $students_result[$student->id]['score'] ?? '-';

    //       if ($score !== '-') {
    //           $percentage = $this->global->marksToPercentage($score, $examInfo->total_marks);
    //           $grade_collective = $this->global->getGrade($percentage,$elevel);
    //           return $grade_collective['grade'];

    //       } else {
    //           return '-';
    //       }

    //   })


    //   ->addColumn('remarks',function($student) use($examInfo,$elevel,$students_result){

    //       $score = $students_result[$student->id]['score'] ?? '-';
    //       if ($score !== '-') {
    //           $percentage = $this->global->marksToPercentage($score, $examInfo->total_marks);
    //           $grade_collective = $this->global->getGrade($percentage,$elevel);
    //           return $grade_collective['remarks'];

    //       } else {
    //           return '-';
    //       }


    //   })

    //   ->addColumn('admission_no', function($student){

    //       return $student->uuid;

    //   })


    //   ->addColumn('action',function($student) use($uuids){

    //      $uuid = $uuids[$student->id]['uuid'] ?? 0;
    //       return ' <span>
    //       <a href="javascript:void(0)" data-uuid="'.$uuid.'"  data-type="text" data-title="Update Marks" class="btn btn-custon-four btn-primary btn-xs editable editable-click"><i class="fa fa-edit"></i></a>
    //       </span> ';

    //   })

    //   ->rawColumns(['action'])
    //   ->make();








    //   }






}
