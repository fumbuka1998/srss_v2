<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\CharacterAssessmentReport;
use App\Models\ClastreamSubject;
use App\Models\Comment;
use App\Models\EducationLevel;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamReport;
use App\Models\GeneratedExamReport;
use App\Models\PredefinedComment;
use App\Models\Result;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\ClassTeacher;
use App\Models\GeneratedReportCharacterAssessment;
use App\Models\StudentResultReport;
use App\Models\StudentSubjectsAssignment;
use App\Models\Subject;
use App\Models\UserHasRoles;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use GlobalHelpers;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf as PdftkPdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Webklex\PDFMerger\PDFMerger as PDFMergerPDFMerger;
use Carbon\Carbon;
// use mikehaertl\pdftk\Pdf as mkpdf;

class ResultsLoaderController extends Controller
{
    public $global;
    public function __construct()
    {
        $this->global = new GlobalHelpers();
    }

    public function index(Request $request)
    {

        $data['streams'] =  Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = SchoolClass::all();
        $data['exam_reports'] = ExamReport::all();

        return view('results.reports.generated.the_results_part')->with($data);
    }



    public function loader(Request $request)
    {


        return $request->all();

        $class_id = $request->class_id;
        $semester = $request->semester;
        $stream_id = $request->stream_id;
        $subject_id = $request->subject_id;
        $report_name = $request->report_name;
        $exam_type = $request->exam_type;
        $academic_year = $request->acdmcyear;

        $elevel = SchoolClass::find($class_id)->education_level_id;

        // return $request->all();

        $data['generated_exam_report'] = $generated_exam_report = GeneratedExamReport::where(['class_id' => $class_id, 'stream_id' => $stream_id, 'term_id' => $semester, 'is_published' => 1, 'academic_year_id' => $academic_year, 'exam_report_id' => $report_name])->first();
        $exam_type_combination = $generated_exam_report->exam_type_combination;
        $class_id = $generated_exam_report->class_id;
        $semester = $generated_exam_report->term_id;
        $stream_id = $generated_exam_report->stream_id;
        $subject_id = $generated_exam_report->subject_id;
        $academic_year = $generated_exam_report->academic_year_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $data['predefined_comments'] = PredefinedComment::all();


        if ($exam_type_combination) {

            // return $exam_type_combination;
            // $exam_type = decrypt($exam_type_combination);
            //
            $exam_type = $exam_type_combination;

            $data['exam_type'] = $exam_type;
            $count  = json_decode(count($exam_type));

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


            $data['subjects'] = $subjects = ClastreamSubject::join('subjects', 'subjects.id', '=', 'clastream_subjects.subject_id')->join('school_classes', 'school_classes.id', '=', 'clastream_subjects.class_id')
                ->join('streams', 'streams.id', '=', 'clastream_subjects.stream_id')
                ->select('streams.name as stream_name', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
                ->where(['clastream_subjects.class_id' => $class_id, 'clastream_subjects.stream_id' => $stream_id])->get();


            $students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
                ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
                ->select('students.id', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
                ->where('students.class_id', $class_id);
            if ($stream_id) {
                $students->where('students.stream_id', $stream_id);
            }

            $data['trigger_hm'] = 0;

            foreach ($matokeo as $key => $tokeo) {
                $checkpoint =  count(Comment::where('student_result_report_id', $tokeo->id)->whereIn('type', ['HM'])->get());

                if ($checkpoint) {
                    $data['trigger_hm'] = 1;
                    break;
                }
            }

            $data['students'] = $students;
            $data['predefined_comments'] = PredefinedComment::all();


            if ($count == 1) {
                $exam = $exam_type[0];
                $exam ?  $examInfo = Exam::find($exam) : $examInfo = '';
                $data['examInfo'] = $examInfo;

                $html = view('results.reports.published.single_exam_all_subjects', $data)->render();
            } elseif ($count > 1) {

                $sbjct_columns = [];
                $colspan = 0;
                $exam_type_columns = array();
                $data['examodel'] = Exam::class;

                foreach ($subjects as $key => $subject) {
                    $code  =   strtolower(str_replace(' ', '_', $subject->sbjct_name));
                    $subjct_span = 0;
                    foreach ($exam_type as $key => $exam) {
                        $exam_model = Exam::find($exam);
                        $subjct_span += 1;
                        $exam_type_columns[] = [
                            'subject_id' => $subject->subject_id,
                            'exam' => $exam_model,
                        ];
                    }
                    $subjct_span += 3;
                    $sbjct_columns[] = [
                        'data' => $subject->sbjct_code,
                        'name' => $code,
                        'subjct_span' => $subjct_span,
                        'subjct_uuid' => $subject->uuid,
                        'subject_id' => $subject->subject_id,
                    ];
                    $colspan += 1;
                }

                /* poooof voala */

                $data['sbjct_columns'] = $sbjct_columns;
                $data['exam_type_columns'] = $exam_type_columns;

                $data['subjects'] = $subjects;
                $data['colspan'] = $colspan  * $subjct_span;

                $html = view('results.reports.published.multiple_exams_all_subjects', $data)->render();
            }
        }

        return response()->json(['html' => $html]);
    }



    public function singleExamTypeReport_myorg(Request $request)
    {



        $pdfMerger = PDFMerger::init();

        $collective = decrypt($request->our_token);

        $generated_exam_report_id = $collective[0]->generated_exam_report_id;

        $generated_exam_report = GeneratedExamReport::find($generated_exam_report_id);
        $stream_id = $generated_exam_report->stream_id;
        $class_id = $generated_exam_report->class_id;
        $term_id = $generated_exam_report->term_id;
        $year = $generated_exam_report->academic_year_id;

        $exam_type_combination = decrypt($generated_exam_report->exam_type_combination);
        $exam_report_id = $generated_exam_report->exam_report_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $student_ids = $request->student_ids;
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

        // return count($exam_type_combination);

        if (count($exam_type_combination) == 1) {

            $exam_type = $exam_type_combination[0];
            $data['exam'] = Exam::find($exam_type);
            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            foreach ($student_ids as $key => $student) {

                // $data['subjects'] =  $subjects->where('student_subjects_assignments.student_id', $student)->get();

                $data['subjects'] = $subjects->where('student_subjects_assignments.student_id', $student)->get()->sortBy('sbjct_id');

                foreach ($collective as $key => $col) {

                    if ($col->student_id == $student) {
                        $data['results'] = $col;
                        $data['metadata'] =  json_decode($col->metadata);
                        $pdf = FacadePdf::loadView('results.reports.printouts.single_exam_type_report',  $data);
                        $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                        $pdf->save($pdfPath);
                        $pdfMerger->addPDF($pdfPath, 'all');
                        $reports[] = $pdfPath;
                    }
                }
            }

            $mergedPdfPath = public_path('reports/temp/' . auth()->user()->id . 'merged_report.pdf');
            $pdfMerger->merge();
            $fileName = basename($mergedPdfPath);
            $pdfMerger->save($mergedPdfPath, "file");

            foreach ($reports as $pdfPath) {
                File::delete($pdfPath);
            }
        } elseif (count($exam_type_combination) > 1) {


            /* dont be afraid human, cuz we got us and ts gon be alright */
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

            /* end */


            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }




            // return $student_ids;
            foreach ($student_ids as $key => $student) {

                // $studentSubjects =  $subjects->where('student_subjects_assignments.student_id', $student)->get();
                //  $data['subjects'] =  $subjects->where('student_subjects_assignments.student_id', $student)->get();
                $data['subjects'] = $subjects->where('student_subjects_assignments.student_id', $student)->get()->sortBy('sbjct_id');

                foreach ($collective as $key => $col) {
                    if ($col->student_id == $student) {
                        // Generate report data for the current student
                        // $data['subjects'] = $studentSubjects;

                        $data['results'] = $init = $col;

                        $generated_report_id =  StudentResultReport::where('generated_exam_report_id', $init->generated_exam_report_id)->first()->id;
                        // my handle
                        // $generated_report_id = null;
                        // if ($init && isset($init->generated_exam_report_id)) {
                        //     $generated_report_id = StudentResultReport::where('generated_exam_report_id', $init->generated_exam_report_id)->first()->id;
                        // }

                        $data['profile_pic'] = Student::find($col->student_id)->profile_pic;
                        $data['metadata'] = $metadata = json_decode($col->metadata);
                        $cst_model = ClassTeacher::where(['stream_id' => Student::find($col->student_id)->stream_id, 'class_id' => Student::find($col->student_id)->class_id, 'flag' => 1])->first();
                        $data['class_teacher'] = $user = User::where('id', $cst_model->teacher_id)->first();


                        // $cmtmodel = Comment::where('student_result_report_id',$generated_report_id)->where('user_id',$user->id)->first();
                        // $data['comment'] = PredefinedComment::find($cmtmodel->predefined_comment_id)->comment;

                        $cmtmodel = Comment::where('student_result_report_id', $generated_report_id)
                            ->where('user_id', $user->id)
                            ->first();

                        if ($cmtmodel) {
                            $predefined_comment_id = $cmtmodel->predefined_comment_id;
                            $predefined_comment = PredefinedComment::find($predefined_comment_id);

                            if ($predefined_comment) {
                                $data['comment'] = $predefined_comment->comment;
                            } else {
                                $data['comment'] = "Needs to Seek Teachers Assistance";
                            }
                        } else {
                            $data['comment'] = "Needs to Seek Teachers Assistance";
                        }



                        $pdf = FacadePdf::loadView('results.reports.printouts.multiple_exam_types_report',  $data);
                        $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                        $pdf->save($pdfPath);
                        $pdfMerger->addPDF($pdfPath, 'all');
                        $reports[] = $pdfPath;
                    }
                }
            }


            $mergedPdfPath = public_path('reports/temp/' . auth()->user()->id . 'merged_report.pdf');
            $pdfMerger->merge();
            $fileName = basename($mergedPdfPath);
            $pdfMerger->save($mergedPdfPath, "file");

            foreach ($reports as $pdfPath) {
                File::delete($pdfPath);
            }
        }


        return response()->json(['reports' => $fileName]);
    }


    //method from tareck local

    public function singleExamTypeReport_working_local_method(Request $request)
    {

        $pdfMerger = PDFMerger::init();

        $collective = decrypt($request->our_token);

        $generated_exam_report_id = $collective[0]->generated_exam_report_id;

        $generated_exam_report = GeneratedExamReport::find($generated_exam_report_id);
        $stream_id = $generated_exam_report->stream_id;
        $class_id = $generated_exam_report->class_id;
        $term_id = $generated_exam_report->term_id;
        $year = $generated_exam_report->academic_year_id;

        $exam_type_combination = decrypt($generated_exam_report->exam_type_combination);
        $exam_report_id = $generated_exam_report->exam_report_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $student_ids = $request->student_ids;
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

        // return count($exam_type_combination);

        if (count($exam_type_combination) == 1) {

            $exam_type = $exam_type_combination[0];
            $data['exam'] = Exam::find($exam_type);
            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            foreach ($student_ids as $key => $student) {

                $data['subjects'] =  $subjects->where('student_subjects_assignments.student_id', $student)->get();

                foreach ($collective as $key => $col) {

                    if ($col->student_id == $student) {
                        $data['results'] = $col;
                        $data['metadata'] =  json_decode($col->metadata);
                        $pdf = FacadePdf::loadView('results.reports.printouts.single_exam_type_report',  $data);
                        $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                        $pdf->save($pdfPath);
                        $pdfMerger->addPDF($pdfPath, 'all');
                        $reports[] = $pdfPath;
                    }
                }
            }

            $mergedPdfPath = public_path('reports/temp/' . auth()->user()->id . 'merged_report.pdf');
            $pdfMerger->merge();
            $fileName = basename($mergedPdfPath);
            $pdfMerger->save($mergedPdfPath, "file");

            foreach ($reports as $pdfPath) {
                File::delete($pdfPath);
            }
        } elseif (count($exam_type_combination) > 1) {


            /* dont be afraid human, cuz we got us and ts gon be alright */
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

            /* end */


            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }




            // return $student_ids;
            foreach ($student_ids as $key => $student) {

                $data['subjects'] =  $subjects->where('student_subjects_assignments.student_id', $student)->get();

                foreach ($collective as $key => $col) {

                    if ($col->student_id == $student) {

                        $data['results'] = $init = $col;

                        $generated_report_id =  StudentResultReport::where('generated_exam_report_id', $init->generated_exam_report_id)->first()->id;

                        $data['profile_pic'] = Student::find($col->student_id)->profile_pic;
                        $data['metadata'] = $metadata = json_decode($col->metadata);
                        //    return $metadata;
                        $cst_model = ClassTeacher::where(['stream_id' => Student::find($col->student_id)->stream_id, 'class_id' => Student::find($col->student_id)->class_id, 'level_flag' => 1])->first();
                        $data['class_teacher'] = $user = User::where('id', $cst_model->teacher_id)->first();

                        $cmtmodel = Comment::where(['student_result_report_id', $generated_report_id, 'student_id' => $metadata->student_id])->where('user_id', $user->id)->first();
                        $data['comment'] = PredefinedComment::find($cmtmodel->predefined_comment_id)->comment;

                        // $cmtmodel = Comment::where('student_result_report_id',$generated_report_id)->where('user_id',$user->id)->first();
                        // $data['comment'] = PredefinedComment::find($cmtmodel->predefined_comment_id)->comment;

                        // $cmtmodel = Comment::where('student_result_report_id', $generated_report_id)
                        //     ->where('user_id', $user->id)
                        //     ->first();

                        // if ($cmtmodel) {
                        //     $predefined_comment_id = $cmtmodel->predefined_comment_id;
                        //     $predefined_comment = PredefinedComment::find($predefined_comment_id);

                        //     if ($predefined_comment) {
                        //         $data['comment'] = $predefined_comment->comment;
                        //     } else {
                        //         $data['comment'] = "Needs to Seek Teachers Assistance";
                        //     }
                        // } else {
                        //     $data['comment'] = "Needs to Seek Teachers Assistance";
                        // }

                        // return $data;

                        $pdf = FacadePdf::loadView('results.reports.printouts.multiple_exam_types_report',  $data);
                        $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                        $pdf->save($pdfPath);
                        $pdfMerger->addPDF($pdfPath, 'all');
                        $reports[] = $pdfPath;
                    }
                }
            }





            $mergedPdfPath = public_path('reports/temp/' . auth()->user()->id . 'merged_report.pdf');
            $pdfMerger->merge();
            $fileName = basename($mergedPdfPath);
            $pdfMerger->save($mergedPdfPath, "file");

            foreach ($reports as $pdfPath) {
                File::delete($pdfPath);
            }
        }


        return response()->json(['reports' => $fileName]);
    }


    // method from remote leo tar 22/04

    public function singleExamTypeReport(Request $request)
    {

        $pdfMerger = PDFMerger::init();

        $collective = decrypt($request->our_token);

        $generated_exam_report_id = $collective[0]->generated_exam_report_id;

        $generated_exam_report = GeneratedExamReport::find($generated_exam_report_id);
        $stream_id = $generated_exam_report->stream_id;
        $class_id = $generated_exam_report->class_id;
        $term_id = $generated_exam_report->term_id;
        $year = $generated_exam_report->academic_year_id;

        $exam_type_combination = decrypt($generated_exam_report->exam_type_combination);
        $exam_report_id = $generated_exam_report->exam_report_id;
        $elevel = SchoolClass::find($class_id)->education_level_id;

        $student_ids = $request->student_ids;
        $reports = [];

        $data = array();

        $data['semester'] = Semester::find($term_id);
        $data['exam_report'] = ExamReport::find($exam_report_id);
        $data['school_class'] = SchoolClass::find($class_id);
        $data['stream'] = Stream::find($stream_id);


        // getting the next semester start date
        $currentSemester = Semester::find($term_id);

        if ($currentSemester) {

            $currentSemesterEndDate = $currentSemester->to;

            // Find the next semester
            $nextSemester = Semester::where('from', '>', $currentSemesterEndDate)
                                    ->orderBy('from', 'asc')
                                    ->first();

            if ($nextSemester) {
                // $data['next_semester_start_date'] = $nextSemester->from;
                $data['next_semester_start_date'] = Carbon::parse($nextSemester->from)->format('F j, Y');
            } else {
                // $data['next_semester_start_date'] = 'No next semester found';
                $data['next_semester_start_date'] = 'No next semester found';

            }
        } else {
            $data['next_semester_start_date'] = 'Current semester not found';
        }





        $subjects = StudentSubjectsAssignment::join('subjects', 'subjects.id', '=', 'student_subjects_assignments.subject_id')->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
            ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
            ->select('streams.name as stream_name', 'student_subjects_assignments.student_id', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
            ->where(['student_subjects_assignments.class_id' => $class_id, 'student_subjects_assignments.stream_id' => $stream_id])
            ->groupBy('subjects.id');


        $data['year'] = AcademicYear::find($year);
        $data['is_signature'] = $is_signature =  $generated_exam_report->include_signature;
        // $data['is_signature'] = $is_signature =  1;


        // getting hm name
        // if($is_signature == 0)
        // {
        //    return $hm_id = UserHasRoles::where('role_id',6)->first()->user_id;
        //    return data['hm_name']= $hm_name = User::where('id', $hm_id)->get();
        // }

        // return count($exam_type_combination);

        if (count($exam_type_combination) == 1) {

            $exam_type = $exam_type_combination[0];
            $data['exam'] = Exam::find($exam_type);
            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            foreach ($student_ids as $key => $student) {

                $data['subjects'] =  $subjects->where('student_subjects_assignments.student_id', $student)->get();

                foreach ($collective as $key => $col) {

                    if ($col->student_id == $student) {
                        $data['results'] = $col;
                        $data['metadata'] =  json_decode($col->metadata);

                        $data['reg_no'] = Student::where("id",$col->student_id)->first()->admission_no;

                        $cst_model = ClassTeacher::where(['stream_id' => Student::find($col->student_id)->stream_id, 'class_id' => Student::find($col->student_id)->class_id, 'level_flag' => 1])->first();
                        $data['class_teacher'] = $user = User::where('id', $cst_model->teacher_id)->first();

                        // return $data;

                        // return $col->student_id;
                        $data['profile_pic'] = Student::find($col->student_id)->profile_pic;
                        $pdf = FacadePdf::loadView('results.reports.printouts.single_exam_type_report',  $data);
                        $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                        $pdf->save($pdfPath);
                        $pdfMerger->addPDF($pdfPath, 'all');
                        $reports[] = $pdfPath;
                    }
                }
            }

            $mergedPdfPath = public_path('reports/temp/' . auth()->user()->id . 'merged_report.pdf');
            $pdfMerger->merge();
            $fileName = basename($mergedPdfPath);
            $pdfMerger->save($mergedPdfPath, "file");

            foreach ($reports as $pdfPath) {
                File::delete($pdfPath);
            }
        } elseif (count($exam_type_combination) > 1) {


            /* dont be afraid human, cuz we got us and ts gon be alright */
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

            /* end */

            // return $student_ids;
            $directoryPath = public_path('reports/temp');

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }


            foreach ($student_ids as $key => $student) {

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

                      $data['comment']  = $init ? $init->ct_comments : "No Comment";

                    //    $generated_report_id =  StudentResultReport::where('generated_exam_report_id', $init->generated_exam_report_id)->first()->id;

                    //  $student_result_reports =  StudentResultReport::where('generated_exam_report_id', $init->generated_exam_report_id)->get();

                    //  loop through these reports

                        // foreach($student_result_reports as $student_result_report)
                        // {
                        //     return  $std_reslt_rep_data = $student_result_report;
                        // }


                        $data['rst'][$student]['profile_pic'] = Student::find($col->student_id)->profile_pic;
                        $data['rst'][$student]['metadata'] = $metadata = json_decode($col->metadata);

                        // $cst_model = ClassTeacher::where(['stream_id'=>Student::find($col->student_id)->stream_id, 'class_id'=>Student::find($col->student_id)->class_id])->first();
                        $cst_model = ClassTeacher::where(['stream_id' => Student::find($col->student_id)->stream_id, 'class_id' => Student::find($col->student_id)->class_id, 'level_flag' => 1])->first();
                        $data['class_teacher'] = $user = User::where('id', $cst_model->teacher_id)->first();

                    //   return  $metadata;

                    if (isset($metadata->results)) {
                        $totalPoints = 0;

                        foreach ($metadata->results as $subject) {
                            if (isset($subject->POINT)) {
                                $totalPoints += $subject->POINT;
                            }
                        }

                        $subjectCount = count((array)$metadata->results);

                        $qualityIndex = $totalPoints / $subjectCount;

                        // Round the quality index to two decimal places
                        $qualityIndex = round($qualityIndex, 2);

                        // Format the quality index to always have two decimal places
                        $qualityIndex = number_format($qualityIndex, 2, '.', '');
                        $data['quality_index'] = $qualityIndex;
                    }



                         $data['student_id'] = $student;

                         $data['student_reg'] = Student::where('id',$student)->first()->admission_no;
                        //  return $init->generated_exam_report_id;

                        // checking for character assessment
                        $data['have_ca'] = $have_ca = GeneratedExamReport::where('id', $init->generated_exam_report_id)->first()->have_ca;
                    //   return  $data['ca'] = CharacterAssessmentReport::where(['generated_exam_report_id'=>$init->generated_exam_report_id, 'student_id'=>$student])->get();
                        $data['ca'] = CharacterAssessmentReport::where([
                            'generated_exam_report_id' => $init->generated_exam_report_id,
                            'student_id' => $student
                        ])->groupBy('code')->get();
                        //  return $data['exam_type'][2];

                         if($have_ca == 1)
                         {
                            // query to get the attendance and late count
                            $attendance = CharacterAssessmentReport::where(['student_id' => $col->student_id, 'generated_exam_report_id' => $col->generated_exam_report_id])->first() ?->attendance;
                             $data['attendance'] =  $attendance ? $attendance : '';

                            $late = CharacterAssessmentReport::where(['student_id' => $col->student_id, 'generated_exam_report_id' => $col->generated_exam_report_id])->first() ?->late;
                            $data['late'] = $late ? $late : '0';

                            $data['total_days'] = 96;

                            //   return $data;

                            $pdf = FacadePdf::loadView('results.reports.printouts.multiple_exam_types_report_with_ca',  $data);
                         }else{
                            $pdf = FacadePdf::loadView('results.reports.printouts.multiple_exam_types_report',  $data);
                         }

                         $pdf->setPaper('a4');

                        // return $data;
                        // return view('results.reports.printouts.multiple_exam_types_report',  $data);

                        $pdfPath = public_path('reports/temp/' . $student . '_report.pdf');
                        $pdf->save($pdfPath);
                        $pdfMerger->addPDF($pdfPath, 'all');
                        $reports[] = $pdfPath;
                    }
                }
            }

            //  return $data['rst'];

            $mergedPdfPath = public_path('reports/temp/' . auth()->user()->id . 'merged_report.pdf');
            $pdfMerger->merge();
            $fileName = basename($mergedPdfPath);
            $pdfMerger->save($mergedPdfPath, "file");

            foreach ($reports as $pdfPath) {
                File::delete($pdfPath);
            }
        }


        return response()->json(['reports' => $fileName]);
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




    public function loader1(Request $req)
    {



        $class_id = $req->class_id;
        $semester = $req->semester;
        $stream_id = $req->stream_id;
        $subject_id = $req->subject_id;
        $academic_year = $req->acdmcyear;

        $exam_type = json_decode(GeneratedExamReport::where('class_id', $class_id)->where('academic_year_id', $academic_year)->where('term_id', $semester)->where('stream_id', $stream_id)->first()->exam_type_combination);


        $elevel = SchoolClass::find($class_id)->education_level_id;

        $data['class_id'] =  $class_id;
        $data['exam_type'] = $exam_type;
        $data['semester'] = $semester;
        $data['stream_id'] = $stream_id;
        $data['subject_id'] = $subject_id;
        $data['academic_year'] = $academic_year;
        $data['elevel']  = $elevel;



        if (count($exam_type) == 1) {

            $exam_type = $exam_type[0];

            $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

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
           <div style="margin-top:2rem" class="fade in animated zoomInDown active">
           <div class="row elevation-2">
           <div class="col-md-12" style="margin-bottom: 1rem">
           <span style="float:right; margin-right: 3.6rem; margin-top: 2rem;">
           <a href="javascript:void(0)" title="Generate Report"  style="color:white;" data-academic-year="' . $academic_year . '" data-class-id="' . $class_id . '" data-semester="' . $semester . '" data-subject_id="' . $exam_type . '" data-elevel="' . $elevel . '"  data-stream-id="' . $stream_id . '"  class="btn generate_single_subject_exam_type btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate </a>
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
            }
            // [
            //     'data' => 'action',
            //     'name' => 'action',
            //     'orderable' => false,
            //     'searchable' => false,
            // ],
            // <th>  </th>


            elseif (!$req->subject_id && $exam_type > 0) {


                $sbjct_columns = [];


                $subjects = Subject::join('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')
                    ->select('subjects.id as subject_id', 'subjects.uuid as sbjct_uuid', 'subjects.code as sbjct_code', 'subjects.name as sbjct_name')
                    ->where('education_level_id', $elevel)->get();
                $sbjcts_html = '';

                $colspan = 0;
                // return $examInfo->total_marks;

                $subject_mark_grade_columns = '';

                // return $subjects;

                foreach ($subjects as $key => $subject) {

                    $sbjcts_html .= '<th style="text-align:center" colspan="2" data-subject_uuid="' . $subject->uuid . '">' . $subject->sbjct_code . ' </th>';
                    $code  =   strtolower(str_replace(' ', '_', $subject->sbjct_name));
                    $sbjct_columns[] = [
                        'data' => $subject->sbjct_code,
                        'name' => $code,
                        'subject_id' => $subject->subject_id,
                    ];
                    // $subjects_count++;
                    $colspan += 1;
                }



                foreach ($sbjct_columns as $key => $column) {
                    $subject_mark_grade_columns .= '
                <th style="text-align:center" data-subject_id="' . $column['subject_id'] . '" class="text-center">Marks</th>
                <th style="text-align:center" data-subject_id="' . $column['subject_id'] . '" class="text-center">Grade</th>';
                }

                // return $subject_mark_grade_columns;
                // return $sbjct_columns;




                /* FROM THE DATATABLE */

                $students = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
                    ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')->orderBy('students.id')
                    ->select('students.id', 'students.firstname', 'students.middlename', 'students.uuid', 'students.lastname')
                    ->where('students.class_id', $class_id);


                if ($stream_id) {

                    $students->where('students.stream_id', $stream_id);
                }


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
                    ->select('results.score', 'results.class_id', 'results.grade_group_id', 'subjects.name as subject_name', 'students.id as admission_no', 'exam_id', 'results.stream_id', 'results.subject_id', 'results.grade_group_id')

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

                foreach ($subjects as $key => $subject) {

                    $code = strtolower(str_replace(' ', '_', $subject['subject_name']));

                    $subjects_array[$code] = $code;
                }

                $kmc = array();

                foreach ($data as $row) {

                    $kmc[$row->admission_no][] = ['student_id' => $row->admission_no, 'subject_id' => $row->subject_id, 'score' => $row->score, 'group_id' => $row->grade_group_id];
                }
                $predefined_comments = PredefinedComment::all();


                $options = '<option> Add Comment.... </option>';
                foreach ($predefined_comments as $key => $pre) {
                    $options .= '<option value="' . $pre->id . '">' . $pre->comment . '</option>';
                }

                //  return $kmc[89];


                $tbody = '';
                // return $students;
                $metadata = array();
                foreach ($students->get() as $key => $student) {

                    $full_name = $student->firstname . ' ' . $student->lastname;

                    $tbody .= '  <tr>

               <td> ' . ++$key . ' </td>
               <td>' . $student->uuid . ' </td>
               <td> ' . $full_name . '</td>';
                    //    return $sbjct_columns;

                    foreach ($sbjct_columns as $key => $column) {
                        $score = '-';
                        $grade = '-';
                        $grade_list = array();
                        $group_id = '';
                        $subject_ids = array();
                        $score_percent = '-';

                        // return $kmc[$student->id];
                        foreach ($kmc[$student->id] as $key => $km) {
                            // return $km;
                            if ($column['subject_id'] == $km['subject_id']) {
                                // return $km;
                                $score =   $km['score'] ??  0;
                                $group_id = $km['group_id'];
                                $score_percent =   $this->global->marksToPercentage($km['score'], $examInfo);
                                $grade = $this->global->getGrade($score, $elevel, $examInfo, $group_id)['grade'];

                                if (!isset($metadata[$student->id])) {
                                    $metadata[$student->id] = [
                                        'passmarks' => [],
                                    ];
                                }

                                // Add the subject details to the 'passmarks' array
                                $metadata[$student->id]['passmarks'][] = [
                                    'subject_id' => $column['subject_id'],
                                    'score' => $score_percent,
                                    'grade' => $grade,
                                ];
                            }
                        }





                        $tbody .= '
                <style>
                .comment-textarea {
                    width: 100%;
                    height: 100px;
                    resize: none; /* Disable textarea resizing */
                    border: 1px solid #ccc;
                    padding: 5px;
                    font-size: 14px;
                }
                .responsive-select-td {
                    position: relative;
                    width: 100%; /* Adjust the width as needed */
                }

                .custom-select {
                    position: relative;
                    width: 100%; /* Adjust the width as needed */
                }

                #responsive-select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    color: #333;
                    font-size: 16px;
                }

                #responsive-select option {
                    font-size: 16px;
                    background-color: #fff;
                    color: #333;
                }

                @media screen and (max-width: 768px) {
                    /* Adjust styles for smaller screens (e.g., mobile devices) */
                    #responsive-select {
                        font-size: 14px;
                    }
                }
                </style>

                    <td style="text-align:center" data-subject_id="' . $column['subject_id'] . '" class="text-center">
                    ' . $score_percent . '
                    </td>
                    <td style="text-align:center" data-subject_id="' . $column['subject_id'] . '" class="text-center"> ' . $grade . '</td>';
                    }

                    $tbody .= '


               <td> </td>
               <td> </td>
               <td> </td>
               <td style="min-width: 24rem;"> <div class="form-group">
               <select name="ct_comment[' . $student->id . ']" class="form-control form-control-sm"> ' . $options . '  </select> </div></td>
               <td style="min-width: 24rem;"> <select name="hm_comment[' . $student->id . ']" class="form-control form-control-sm"> ' . $options . '  </select> </td>
            ';




                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>
                    //    <td> </td>

                    //    </tr>';


                }


                // return $tbody;


                /* END OF DATATABLE */

                // return  $sbjct_columns;

                $indexToInsert = 2; // Adjust this index as needed
                // array_splice($data['dynamicColumns'], $indexToInsert + 1, 0, $sbjct_columns);
                $colspan = $colspan * 2;
                $report_name = $req->report_name;

                $jt = json_encode($metadata);

                $base_html = '
                <div class="row">
                <div class="col-lg-12" style="margin-bottom: 5rem">
                <div style="margin-top:2rem" class="fade in animated zoomInDown active">
                <div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
                <div class="col-md-12" style="margin-bottom: 1rem">
                <span style="float:right">
                <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_single_subject_exam_type btn btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate </a>
                <a href="javascript:void(0)" title="pdf"  style="color:white; display:none"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
                </span>

                </div>

                    <div class="col-md-12">

                    <form id="single_exam_type_no_subject">

                    <input type="hidden" id="exam" name="exam_type[]" value="' . $exam_type . '">
                    <input type="hidden" id="class_id" name="class_id" value="' . $class_id . '">
                    <input type="hidden" id="stream_id" name="stream_id" value="' . $stream_id . '">
                    <input type="hidden" id="subject_id" name="subject_id" value="' . $subject_id . '">
                    <input type="hidden" id="report_name" name="report_name" value="' . $exam_type . '">
                    <input type="hidden" id="academic_year_id" name="academic_year" value="' . $academic_year . '">
                    <input type="hidden" id="report_name" name="report_name" value="' . $report_name . '">
                    <input type="hidden" id="term_id" name="term_id" value="' . $semester . '">
                    <input type="hidden" id="metadata" name="metadata" value=' . $jt . '>

                    <style>

                    .table-container {

                        overflow-x: scroll; /* Enable horizontal scroll */
                        border: 1px solid #ccc; /* Add a border to the container for styling */
                        position: relative;
                    }

                    // .table-wrapper {
                    //     /* Add some space for the frozen columns */
                    //     padding-left: 1rem; /* Adjust the width to match the first 3 columns */
                    // }

                    .table {
                        width: auto; /* Allow the table to expand horizontally */
                        border-collapse: collapse; /* Optional: Collapse table borders */
                    }

                    /* Optional: Add styles to table rows and cells */
                    .table tr {
                        background-color: #f9f9f9; /* Row background color */
                    }

                    .table td, .table th {
                        padding: 8px; /* Cell padding */
                        border: 1px solid #ddd; /* Cell border */
                    }

                    /* Optional: Style table header */
                    .table th {
                        background-color: #069613; /* Header background color */
                        color: #fff; /* Header text color */
                    }

                    /* Freeze the first 3 columns */
                    .table-wrapper th:nth-child(-n+3),
                    .table-wrapper td:nth-child(-n+3) {
                        position: -webkit-sticky;
                        position: sticky;
                        left: 0;
                        z-index: 1;
                        // background-color: #fff; /* Background color for frozen columns */
                    }





                    </style>

                    <div class="table-container">
                    <div class="table-wrapper">
                        <table class="table table-bordered" id="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="frozen" rowspan="3">SN</th>
                                    <th rowspan="3" class="frozen" style="text-align: center; width:20rem">ADMISSION NUMBER</th>
                                    <th style="min-width:25rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                                    <th style="text-align:center" class="text-center" colspan="' . $colspan . '">
                                    SUBJECTS  (x/ ' . $examInfo->total_marks . ')
                                    </th>
                                    <th style="text-align:center" rowspan="3"> AVG </th>
                                    <th style="text-align:center" rowspan="3"> DIV </th>
                                    <th style="text-align:center" rowspan="3"> POINTS </th>
                                    <th style="text-align:center" rowspan="3"> C/T Comments </th>
                                    <th style="text-align:center" rowspan="3"> H/M comments </th>
                                </tr>
                                <tr>

                                ' . $sbjcts_html . '


                                </tr>

                                <tr>


                                ' . $subject_mark_grade_columns . '

                                <tr/>


                            </thead>

                            <tbody>


                            ' . $tbody . '



                            </tbody>


                        </table>
                        </div>
                            </div>
                        </form>
                    </div>

                    <div class="row">

                    <div class="col-md-3">
                        Include CP
                    </div>

                    <div class="col-md-3">
                    Include SP
                    </div>


                    <div class="col-md-3">

                    </div>



                </div>
            </div>
                ';

                $data['subjects'] = $subjects;
            } elseif ($req->subject_id && !$req->exam_type) {

                $exams =  Exam::all();


                $data['dynamicColumns'] = [
                    ['data' => 'sn', 'name' => 'sn'],
                    ['data' => 'admission_no', 'name' => 'admission_no', 'orderable' => false],
                    ['data' => 'full_name', 'name' => 'full_name'],
                    ['data' => 'avg', 'name' => 'avg'],
                    ['data' => 'grade', 'name' => 'grade'],
                    [
                        'data' => 'remarks', 'name' => 'remarks', 'orderable' => false,
                        'searchable' => false,
                    ],
                    [
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                    ],
                ];


                $exams_html = '';
                $exam_columns = [];
                $odds = '';
                $colspan = 0;

                foreach ($exams as $key => $exam) {

                    $exams_html .= '<th data-exam_uuid="' . $exam->uuid . '">' . $exam->name . ' </th>';
                    $odds .= '<th>x/' . $exam->total_marks . ' </th>';
                    $code  =   strtolower(str_replace(' ', '_', $exam->name));
                    $exam_columns[] = [
                        'data' => $code,
                        'name' => $code,
                    ];
                    $colspan += 1;
                }
                $indexToInsert = 2; // Adjust this index as needed
                array_splice($data['dynamicColumns'], $indexToInsert + 1, 0, $exam_columns);


                $base_html = '
           <div style="margin-top:2rem" class="fade in animated zoomInDown active">
           <div class="row">
           <div class="row elevation-2">
           <div class="col-md-12" style="margin-bottom: 1rem">
           <span style="float:right">
           <a href="javascript:void(0)" title="excel"  style="color:white"  class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i> Excelss </a>
           <a href="javascript:void(0)" title="pdf"  style="color:white"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
           </span>
           </div>

               <div class="col-md-12">
                   <table class="table table-bordered x-editor-custom" id="table" style="width:100%">
                       <thead>
                       <tr>
                       <th rowspan="2">SN</th>
                       <th rowspan="2" style="text-align: center; width:20rem">ADMISSION NUMBER</th>
                       <th class="text-center" rowspan="2">FULL NAME</th>

                       ' . $odds . '

                       <th rowspan="2"> Avg </th>
                       <th rowspan="2"> Grade </th>
                       <th rowspan="2"> Remarks</th>
                       <th rowspan="2"> action </th>

                   </tr>
                   <tr>

                   ' . $exams_html . '



                   </tr>


                       </thead>
                   </table>
               </div>
           </div>
       </div>
           ';
            } elseif ($class_id && !$subject_id && !$exam_type && $semester && $academic_year) {




                $sbjct_columns = [];

                $subjects = Subject::join('subject_education_levels', 'subjects.id', '=', 'subject_education_levels.subject_id')->where('education_level_id', $elevel)->get();
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



            $data['dynamicColumns'] = [
                ['data' => 'sn', 'name' => 'sn'],
                ['data' => 'admission_no', 'name' => 'admission_no', 'orderable' => false],
                ['data' => 'full_name', 'name' => 'full_name'],
                [
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],

            ];
        }

        $base_html = '
       <div style="margin-top:2rem" class="fade in animated zoomInDown active">
       <div class="row">


       <div style="margin-top:2rem" class="fade in animated zoomInDown active">
       <div class="row elevation-2">
       <div class="col-md-12" style="margin-bottom: 1rem">
       <span style="float:right">
       <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="btn btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Excel </a>
       <a href="javascript:void(0)" title="excel"    class="btn btn-warning btn-sm"> <i class="fa fa-file-pdf"></i> Pdf </a>
       </span>
       </div>

           <div class="col-md-12">
               <table class="table table-bordered x-editor-custom" id="table" style="width:100%">
                   <thead>
                       <tr>
                           <th>SN</th>
                           <th style="text-align: center; width:20rem">Admission Number</th>
                           <th>Full Name</th>
                           <th>  </th>
                       </tr>

                   </thead>
               </table>
           </div>
       </div>
   </div>
       ';


        $data['base_html'] = $base_html;


        return  response($data);
    }
}
