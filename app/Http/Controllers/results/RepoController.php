<?php

namespace App\Http\Controllers\results;

use App\Http\Controllers\Controller; 
use App\Models\AcademicYear;
use App\Models\ClassTeacher;
use App\Models\Comment;
use App\Models\EscalationLevel;
use App\Models\Exam;
use App\Models\ExamReport;
use App\Models\GeneratedExamReport;
use App\Models\PredefinedComment;
use App\Models\Result;
use App\Models\SchoolClass;
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

class ReportsController extends Controller
{

   public $global;
    public function __construct(){

        $this->global = new GlobalHelpers();
    }

    public function newIndex(Request $request){


        return view('results.reports.new_index');

    }

    public function generateClassReport(

        $academicYearId=null,
        $termId = null,
        $classId = null,
        $subjectId = null,
        $examId = null,
        $streamId = null,
        Request $req

        ){



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
      $assignment =  ClassTeacher::where('teacher_id',auth()->user()->id)->first();

      if ($assignment) {

        // return 'tunafika apa';

    $school_classes = $data['school_classes'] =  SchoolClass::join('streams','school_classes.id','=','streams.class_id')
        ->select('school_classes.name as class_name','school_classes.id as class_id','streams.id as stream_id','streams.name as stream_name')
        ->where('school_classes.id', $assignment->class_id)
        ->where('streams.id',$assignment->stream_id)
       ->first();

      }elseif (auth()->user()->hasRole('Admin')) {

        $school_classes = $data['school_classes'] =  SchoolClass::join('streams','school_classes.id','=','streams.class_id')
        ->select(
            'school_classes.id as class_id','streams.name as stream_name',
            'streams.id as stream_id','school_classes.name as class_name'
            )->get();


      }

        $data['classes'] = SchoolClass::all();

        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] =  $streams;
        $data['academic_years'] = AcademicYear::all();

        return view('results.reports.index')->with($data);

    }


    public function load(Request $req){

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



        if (count($exam_type) == 1 ) {

        $exam_type = $exam_type[0];

        $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

        if ($req->subject_id && $req->exam_type) {


            $data['dynamicColumns'] = [
                ['data' => 'sn', 'name' => 'sn'],
                ['data' => 'admission_no', 'name' => 'admission_no','orderable' => false],
                ['data' => 'full_name', 'name' => 'full_name'],
                ['data' => 'score', 'name' => 'score'],
                ['data' => 'percentage', 'name' => 'percentage', 'orderable' => false],
                ['data' => 'grade', 'name' => 'grade'],
                ['data' => 'remarks', 'name' => 'remarks','orderable' => false, 'searchable' => false,],


            ];


           $base_html = '
           <div style="margin-top:2rem" class="fade in animated zoomInDown active">
           <div class="row elevation-2">
           <div class="col-md-12" style="margin-bottom: 1rem">
           <span style="float:right; margin-right: 3.6rem; margin-top: 2rem;">
           <a href="javascript:void(0)" title="Generate Report"  style="color:white;" data-academic-year="'.$academic_year.'" data-class-id="'.$class_id.'" data-semester="'.$semester.'" data-subject_id="'.$exam_type.'" data-elevel="'.$elevel.'"  data-stream-id="'.$stream_id.'"  class="btn generate_single_subject_exam_type btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate </a>
           <a href="javascript:void(0)" title="excel"  style="color:white; display:none"  class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i> Excel </a>
           <a target="_blank" href="'.route('results.single.subject.examtype.checked.pdf',[$academic_year,$class_id,$semester,$subject_id,$exam_type,$elevel,$stream_id]).'" title="pdf"  style="color:white;"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
           </span>
           </div>

               <div class="col-md-12" style="padding-left: 5rem;  padding-right: 5rem;">
                   <table class="table table-bordered x-editor-custom" id="table" style="width:100%">
                       <thead>
                           <tr>
                               <th>SN</th>
                               <th style="text-align: center; width:20rem">Admission Number</th>
                               <th>Full Name</th>
                               <th>marks/'.$examInfo->total_marks.'</th>
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


        elseif (!$req->subject_id && $req->exam_type) {

    $sbjct_columns = [];
       $subjects = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
       ->select('subjects.id as subject_id','subjects.uuid as sbjct_uuid','subjects.code as sbjct_code','subjects.name as sbjct_name')
                            ->where('education_level_id',$elevel)->get();
       $sbjcts_html = '';
       $colspan = 0;
        // return $examInfo->total_marks;

        $subject_mark_grade_columns = '';

        // return $subjects;

            foreach ($subjects as $key => $subject) {

                $sbjcts_html.= '<th style="text-align:center" colspan="2" data-subject_uuid="'.$subject->uuid.'">'.$subject->sbjct_code.' </th>';
                $code  =   strtolower( str_replace(' ','_',$subject->sbjct_name));
                $sbjct_columns[] = [
                    'data'=> $subject->sbjct_code,
                    'name'=> $code,
                    'subject_id'=> $subject->subject_id,
                ];
                // $subjects_count++;
                $colspan +=1;

            }

            foreach ($sbjct_columns as $key => $column) {
                $subject_mark_grade_columns.='
                <th style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">Marks</th>
                <th style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">Grade</th>';
            }

            // return $subject_mark_grade_columns;
            // return $sbjct_columns;




            /* FROM THE DATATABLE */

            $students = Student::join('school_classes','students.class_id','=','school_classes.id')
            ->leftjoin('streams','students.stream_id','=','streams.id')->orderBy('students.id')
            ->select('students.id','students.firstname','students.middlename','students.uuid','students.lastname')
            ->where('students.class_id',$class_id);


            if ($stream_id) {

             $students->where('students.stream_id', $stream_id);

         }


     $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
         ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
         ->leftjoin('results', 'students.id', '=', 'results.student_id')
         ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
         ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
         ->leftjoin('exams','results.exam_id','=','exams.id')
         ->join('subjects', 'results.subject_id', 'subjects.id')
         ->where('results.academic_year_id',$academic_year)
         ->where('results.class_id',$class_id)
         ->where('semesters.id',$semester)
         ->select('results.score','results.class_id','results.grade_group_id','subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','results.subject_id','results.grade_group_id')
         ->where('results.status','COMPLETED')
         ->groupBy('students.id','subjects.id');


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
    if (!count($data)) {


        $data['base_html'] = 'no results';


        return  response($data);
        # code...
    }

 foreach ($subjects as $key => $subject) {

      $code = strtolower( str_replace(' ','_',$subject['subject_name']));

     $subjects_array[$code] = $code;

 }

$kmc = array();

 foreach ($data as $row) {

     $kmc[$row->admission_no][] = ['student_id'=>$row->admission_no, 'subject_id'=>$row->subject_id, 'score'=>$row->score,'group_id'=>$row->grade_group_id];

 }


 $predefined_comments = PredefinedComment::all();


 $options = '<option> Add Comment.... </option>';
 foreach ($predefined_comments as $key => $pre) {
   $options .= '<option value="'.$pre->id.'">'.$pre->comment.'</option>';
 }

//  return $kmc[89];


            $tbody = '';
            // return $students;
            $metadata = array();
            foreach ($students->get() as $key => $student) {

                $full_name = $student->firstname.' '.$student->lastname;

               $tbody.= '  <tr>

               <td> '.++$key.' </td>
               <td>'.$student->uuid.' </td>
               <td> '.$full_name.'</td>';
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
                        $score_percent =   $this->global->marksToPercentage($km['score'],$examInfo);
                        $grade = $this->global->getGrade($score,$elevel,$examInfo,$group_id)['grade'];
                        $remarks = $this->global->getGrade($score,$elevel,$examInfo,$group_id)['remarks'];

                        if (!isset($metadata[$student->id])) {
                            $metadata[$student->id] = [
                                'passmarks' => [],
                            ];
                        }

                        // Add the subject details to the 'passmarks' array
                        $metadata[$student->id]['passmarks'][] = [
                            'subject_id' => $column['subject_id'],
                            'subject_name'=> Subject::find($column['subject_id'])->name,
                            'subject_code'=> Subject::find($column['subject_id'])->code,
                            'score_percent' => $score_percent,
                            'score'=>$score,
                            'remarks'=>$remarks,
                            'grade' => $grade,
                        ];



                    }

                }

                // return $metadata;





                $tbody.='
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

                    <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">
                    '.$score_percent.'
                    </td>
                    <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center"> '.$grade.'</td>';

            }

            $tbody.='
               <td> </td>
               <td> </td>
               <td> </td>
               <td style="min-width: 24rem;"> <div class="form-group">
               <select name="ct_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </div></td>
            ';


            }
            $indexToInsert = 2;
            $colspan = $colspan * 2;
            $report_name = $req->report_name;

             $jt = encrypt($metadata);

                $base_html = '
                <div class="row">
                <div class="col-lg-12" style="margin-bottom: 5rem">
                <div style="margin-top:2rem" class="fade in animated zoomInDown active">
                <div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
                <div class="col-md-12" style="margin-bottom: 1rem">
                <span style="float:right">
                <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_single_subject_exam_type btn btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate  Report </a>
                <a href="javascript:void(0)" title="pdf"  style="color:white; display:none"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
                </span>

                </div>

                    <div class="col-md-12">

                    <form id="single_exam_type_no_subject">

                    <input type="hidden" id="exam" name="exam_type[]" value="'.$exam_type.'">
                    <input type="hidden" id="class_id" name="class_id" value="'.$class_id.'">
                    <input type="hidden" id="stream_id" name="stream_id" value="'.$stream_id.'">
                    <input type="hidden" id="subject_id" name="subject_id" value="'.$subject_id.'">
                    <input type="hidden" id="report_name" name="report_name" value="'.$exam_type.'">
                    <input type="hidden" id="academic_year_id" name="academic_year" value="'.$academic_year.'">
                    <input type="hidden" id="report_name" name="report_name" value="'.$report_name.'">
                    <input type="hidden" id="term_id" name="term_id" value="'.$semester.'">
                    <input type="hidden" id="metadata" name="metadata" value='.$jt.'>

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
                                    <th style="text-align:center" class="text-center" colspan="'.$colspan.'">
                                    SUBJECTS  (x/ '.$examInfo->total_marks.')
                                    </th>
                                    <th style="text-align:center" rowspan="3"> AVG </th>
                                    <th style="text-align:center" rowspan="3"> DIV </th>
                                    <th style="text-align:center" rowspan="3"> POINTS </th>
                                    <th style="text-align:center" rowspan="3"> C/T Comments </th>
                                </tr>
                                <tr>

                                '.$sbjcts_html.'


                                </tr>

                                <tr>


                                '.$subject_mark_grade_columns.'

                                <tr/>


                            </thead>

                            <tbody>


                            '.$tbody.'



                            </tbody>


                        </table>
                        </div>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
                ';

            $data['subjects'] = $subjects;

        }

        elseif ($req->subject_id && !$req->exam_type) {

       $exams =  Exam::all();


            $data['dynamicColumns'] = [
                ['data' => 'sn', 'name' => 'sn'],
                ['data' => 'admission_no', 'name' => 'admission_no','orderable' => false],
                ['data' => 'full_name', 'name' => 'full_name'],
                ['data'=>'avg','name'=>'avg'],
                ['data'=>'grade','name'=>'grade'],
                ['data'=>'remarks','name'=>'remarks','orderable' => false,
                'searchable' => false,],
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

                $exams_html.= '<th data-exam_uuid="'.$exam->uuid.'">'.$exam->name.' </th>';
                $odds .= '<th>x/'.$exam->total_marks.' </th>';
                $code  =   strtolower( str_replace(' ','_',$exam->name));
                $exam_columns[] = [
                    'data'=> $code,
                    'name'=> $code,
                ];
                $colspan +=1;

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

                       '.$odds.'

                       <th rowspan="2"> Avg </th>
                       <th rowspan="2"> Grade </th>
                       <th rowspan="2"> Remarks</th>
                       <th rowspan="2"> action </th>

                   </tr>
                   <tr>

                   '.$exams_html.'



                   </tr>


                       </thead>
                   </table>
               </div>
           </div>
       </div>
           ';

        }



        elseif ($class_id && !$subject_id && !$exam_type && $semester &&$academic_year) {




        $sbjct_columns = [];

        $subjects = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')->where('education_level_id',$elevel)->get();
        $sbjcts_html = '';

             $colspan = 0;

             $data['dynamicColumns'] = [
             ['data' => 'sn', 'name' => 'sn'],
             ['data'=>'gender', 'name'=>'gender'],
             ['data' => 'full_name', 'name' => 'full_name'],
             ['data' => 'admission_no', 'name' => 'admission_no','orderable' => false],
             [
                 'data' => 'action',
                 'name' => 'action',
                 'orderable' => false,
                 'searchable' => false,
             ],

         ];


             foreach ($subjects as $key => $subject) {

                 $sbjcts_html.= '<th data-subject_uuid="'.$subject->uuid.'">'.$subject->code.' </th>';
                 $code  =   strtolower( str_replace(' ','_',$subject->name));
                 $sbjct_columns[] = [
                     'data'=> $code,
                     'name'=> $code,
                 ];
                 $colspan +=1;

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
                                     <th style="text-align:center" class="text-center" colspan="'.$colspan.'">
                                     TERM 1
                                     </th>
                                     <th rowspan="2"> action </th>
                                 </tr>
                                 <tr>

                                 '.$sbjcts_html.'


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


      }elseif (count($exam_type) > 1) {


/* tunaanzia hapa */
/* ******************************************************************************************* */
/*          ******************************************************* */
/*                          ******************************                   */


// return $req->all();
$elevel = SchoolClass::find($req->class_id)->education_level_id;
$sbjct_columns = [];
 $subjects = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
->select('subjects.id as subject_id','subjects.uuid as sbjct_uuid','subjects.code as sbjct_code','subjects.name as sbjct_name')
                     ->where('education_level_id',$elevel)->get();
$sbjcts_html = '';
$colspan = 0;
 // return $examInfo->total_marks;

 $subject_mark_grade_columns = '';

 // return $subjects;

     foreach ($subjects as $key => $subject) {

         $sbjcts_html.= '<th style="text-align:center" colspan="2" data-subject_uuid="'.$subject->uuid.'">'.$subject->sbjct_code.' </th>';
         $code  =   strtolower( str_replace(' ','_',$subject->sbjct_name));
         $sbjct_columns[] = [
             'data'=> $subject->sbjct_code,
             'name'=> $code,
             'subject_id'=> $subject->subject_id,
         ];
         // $subjects_count++;
         $colspan +=1;

     }

     $exam_types_html = '';
     foreach ($exam_type as $key => $exam) {
       $exam = Exam::find($exam);
        $exam_types_html.='
        <th style="text-align:center" data-subject_id="" class="text-center">'.$exam->code.'/'.$exam->total_marks.'</th>';
     }
     $exam_types_html.= '<th style="text-align:center" data-subject_id=""  class="text-center">AVG</th> <th style="text-align:center" data-subject_id=""  class="text-center">GRADE</th> <th style="text-align:center" data-subject_id=""  class="text-center">POINTS</th>';


     $students = Student::join('school_classes','students.class_id','=','school_classes.id')
     ->leftjoin('streams','students.stream_id','=','streams.id')->orderBy('students.id')
     ->select('students.id','students.firstname','students.middlename','students.uuid','students.lastname')
     ->where('students.class_id',$class_id);

   return  $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
  ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
  ->leftjoin('results', 'students.id', '=', 'results.student_id')
  ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
  ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
  ->leftjoin('exams','results.exam_id','=','exams.id')
  ->join('subjects', 'results.subject_id', 'subjects.id')
  ->where('results.academic_year_id',$academic_year)
  ->where('results.class_id',$class_id)
  ->where('semesters.id',$semester)
  ->whereIn('results.exam_id',$exam_type)
  ->select('results.score','results.class_id','results.grade_group_id','subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','results.subject_id','results.grade_group_id')
  ->where('results.status','COMPLETED')
  ->groupBy('students.id','subjects.id');


     /* hadi hapa leo */


     $tbody = '';
     // return $students;
     $metadata = array();
     foreach ($students->get() as $key => $student) {

         $full_name = $student->firstname.' '.$student->lastname;

        $tbody.= '  <tr>

        <td> '.++$key.' </td>
        <td>'.$student->uuid.' </td>
        <td> '.$full_name.'</td>';



     /* next step */



     /* end next step */


     /* final step */





     /* end final step */


    //  foreach ($sbjct_columns as $key => $column) {
    //      $subject_mark_grade_columns.='
    //      <th style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">Marks</th>
    //      <th style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">Grade</th>';
    //  }

     // return $subject_mark_grade_columns;
     // return $sbjct_columns;




     /* FROM THE DATATABLE */




     if ($stream_id) {

      $students->where('students.stream_id', $stream_id);

  }


// $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
//   ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
//   ->leftjoin('results', 'students.id', '=', 'results.student_id')
//   ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
//   ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
//   ->leftjoin('exams','results.exam_id','=','exams.id')
//   ->join('subjects', 'results.subject_id', 'subjects.id')
//   ->where('results.academic_year_id',$academic_year)
//   ->where('results.class_id',$class_id)
//   ->where('semesters.id',$semester)
//   ->select('results.score','results.class_id','results.grade_group_id','subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','results.subject_id','results.grade_group_id')
//   ->where('results.status','COMPLETED')
//   ->groupBy('students.id','subjects.id');


//   if ($exam_type) {
//       $data->where('results.exam_id', $exam_type);
//   }
  if ($stream_id) {
      $data->where('results.stream_id', $stream_id);
  }
  if ($subject_id) {
      $data->where('results.subject_id', $subject_id);
  }

$data = $data->get();

if (!count($data)) {


 $data['base_html'] = 'no results';


 return  response($data);
 # code...
}

foreach ($subjects as $key => $subject) {

$code = strtolower( str_replace(' ','_',$subject['subject_name']));

$subjects_array[$code] = $code;

}

$kmc = array();

foreach ($data as $row) {

$kmc[$row->admission_no][] = ['student_id'=>$row->admission_no, 'subject_id'=>$row->subject_id, 'score'=>$row->score,'group_id'=>$row->grade_group_id,'exam_id'=>$row->exam_id];

}


$predefined_comments = PredefinedComment::all();


$options = '<option> Add Comment.... </option>';
foreach ($predefined_comments as $key => $pre) {
$options .= '<option value="'.$pre->id.'">'.$pre->comment.'</option>';
}

//  return $kmc[89];


     $tbody = '';
     // return $students;
     $metadata = array();
     foreach ($students->get() as $key => $student) {

         $full_name = $student->firstname.' '.$student->lastname;

        $tbody.= '  <tr>

        <td> '.++$key.' </td>
        <td>'.$student->uuid.' </td>
        <td> '.$full_name.'</td>';
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
                 $score_percent =   $this->global->marksToPercentage($km['score'],$examInfo);
                 $grade = $this->global->getGrade($score,$elevel,$examInfo,$group_id)['grade'];
                 $remarks = $this->global->getGrade($score,$elevel,$examInfo,$group_id)['remarks'];

                 if (!isset($metadata[$student->id])) {
                     $metadata[$student->id] = [
                         'passmarks' => [],
                     ];
                 }

                 // Add the subject details to the 'passmarks' array
                 $metadata[$student->id]['passmarks'][] = [
                     'subject_id' => $column['subject_id'],
                     'subject_name'=> Subject::find($column['subject_id'])->name,
                     'subject_code'=> Subject::find($column['subject_id'])->code,
                     'score_percent' => $score_percent,
                     'score'=>$score,
                     'remarks'=>$remarks,
                     'grade' => $grade,
                 ];



             }

         }

         // return $metadata;





         /* GAME CHANGER */


         return 'hakuna kitu apa'
;
























    // $viewContent = view('results.reports.templates.multiple_exams',$data)->render();
    // return response()->json(['base_html' => $viewContent]);


     /* hadi hapa leo */
     $tbody = '';
     // return $students;
     $metadata = array();


     if ($stream_id) {

      $students->where('students.stream_id', $stream_id);

  }

  if ($stream_id) {
      $data->where('results.stream_id', $stream_id);
  }
  if ($subject_id) {
      $data->where('results.subject_id', $subject_id);
  }

// return $data = $data->get();

if (!count($data->get())) {

$data['base_html'] = 'no results';

}else{
    $indexToInsert = 2;
    $colspan = ($colspan * 2) * $subjct_span;
    $report_name = $req->report_name;
    $exam_type = encrypt($exam_type);

    $base_html = '
    <div class="row">
    <div class="col-lg-12" style="margin-bottom: 5rem">
    <div style="margin-top:2rem" class="fade in animated zoomInDown active">
    <div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
    <div class="col-md-12" style="margin-bottom: 1rem">
    <span style="float:right">
    <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_single_subject_exam_type btn btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate  Report </a>
    <a href="javascript:void(0)" title="pdf"  style="color:white; display:none"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
    </span>

    </div>

        <div class="col-md-12">

        <form id="single_exam_type_no_subject">

        <input type="hidden" id="exam" name="exam_type[]" value="'.$exam_type.'">
        <input type="hidden" id="class_id" name="class_id" value="'.$class_id.'">
        <input type="hidden" id="stream_id" name="stream_id" value="'.$stream_id.'">
        <input type="hidden" id="subject_id" name="subject_id" value="'.$subject_id.'">
        <input type="hidden" id="academic_year_id" name="academic_year" value="'.$academic_year.'">
        <input type="hidden" id="report_name" name="report_name" value="'.$report_name.'">
        <input type="hidden" id="term_id" name="term_id" value="'.$semester.'">
        <input type="hidden" id="metadata" name="metadata" value="">

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
                        <th style="text-align:center" class="text-center" colspan="'.$colspan.'">
                        SUBJECTS
                        </th>
                        <th style="text-align:center" rowspan="3"> AVG </th>
                        <th style="text-align:center" rowspan="3"> DIV </th>
                        <th style="text-align:center" rowspan="3"> POINTS </th>
                        <th style="text-align:center" rowspan="3"> C/T Comments </th>
                    </tr>
                    <tr>

                    '.$sbjcts_html.'


                    </tr>';
                    $tr = '';

                    // return $exam_type_columns;

                    for ($i=0; $i<count($sbjct_columns);  $i++) {
                        // return $col;
                        foreach ($exam_type_columns as $key => $coltype) {
                            // return $coltype;

                            if ($sbjct_columns[$i]['subject_id'] == $coltype['subject_id']) {

                                $tr .= '<th>'.$coltype['exam']->code .'/ '.$coltype['exam']->total_marks.'</th>';

                            }

                        }

                        $tr .= '<th style="text-align:center" data-subject_id="" class="text-center">AVG %</th> <th style="text-align:center" data-subject_id=""  class="text-center">GRADE</th> <th style="text-align:center" data-subject_id=""  class="text-center">POINTS</th>';

                    }

                    $base_html.=' '.$tr.'

                </thead>
                <tbody>';

                $tbody = '';

                foreach ($students->get() as $key => $student) {



                $full_name = $student->firstname.' '.$student->lastname;
                   $tbody.= '  <tr>

                   <td> '.++$key.' </td>
                   <td>'.$student->uuid.' </td>
                   <td> '.$full_name.'</td>';

                   $typos = decrypt($exam_type);
                    // return count($sbjct_columns);
                    // return $exam_type_columns;
                   for ($j=0; $j<count($sbjct_columns);  $j++) {

                        // return $sbjct_columns;
                    // return count($exam_type_columns);

                        for ($i=0; $i<count($typos); $i++) {

                            // if ($coltype['subject_id'] ==   $sbjct_columns[$i]['subject_id'] ) {
                                // return $sbjct_columns[2];

                              $filtered =  $data->where('results.student_id', $student->id)->where('results.subject_id',$sbjct_columns[2]['subject_id'])->where('results.exam_id',$typos[$i])->first();
                                if (isset($filtered)) {

                                    $tbody.= '<td> '.$filtered->score.' </td> ';
                                    // break;

                                }

                                // $tbody.='<td> AVG  </td> <td> GRADE  </td> <td> POINTS  </td>';
                            }


                        }



                    }

                    // return $tbody;






                // $filtered_data = $data->where('students.id',$student->id)->groupBy('students.id','subjects.id','exams.id')->get();

                //   $full_name = $student->firstname.' '.$student->lastname;

                //    $tbody.= '  <tr>

                //    <td> '.++$key.' </td>
                //    <td>'.$student->uuid.' </td>
                //    <td> '.$full_name.'</td>';
                //     $count = 0;
                //     if (count($filtered_data)) {

                //         foreach ($filtered_data as $ts => $dt) {


                //             foreach ($exam_type_columns as $key => $col) {
                //                 $count++;
                //                 if ($col['subject_id'] == $dt->subject_id && $dt->exam_id == $col['exam']->id ) {

                //                     if ($dt->score) {
                //                         $tbody .= '<td>'.$dt->score.'</td>';
                //                     }else{
                //                         $tbody .= '<td>-</td>';
                //                     }


                //                 }

                //             }

                //         }




                //     }



        }



        $tbody.= ' </tr> ';
        $base_html .= $tbody;
            '</tbody>
            </table>
            </div>
                </div>
            </form>
        </div>

    </div>
</div>'
;

        }

        $base['base_html'] = $base_html;
        return  response($base);







         /* HERE WE COMEEEEE */




         $tbody.='
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

             <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">
             '.$score_percent.'
             </td>
             <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center"> '.$grade.'</td>';

     }

     $tbody.='
        <td> </td>
        <td> </td>
        <td> </td>
        <td style="min-width: 24rem;"> <div class="form-group">
        <select name="ct_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </div></td>
     ';


     }
     $indexToInsert = 2;
     $colspan = $colspan * 2;
     $report_name = $req->report_name;

      $jt = encrypt($metadata);

         $base_html = '
         <div class="row">
         <div class="col-lg-12" style="margin-bottom: 5rem">
         <div style="margin-top:2rem" class="fade in animated zoomInDown active">
         <div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
         <div class="col-md-12" style="margin-bottom: 1rem">
         <span style="float:right">
         <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_single_subject_exam_type btn btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate  Report </a>
         <a href="javascript:void(0)" title="pdf"  style="color:white; display:none"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
         </span>

         </div>

             <div class="col-md-12">

             <form id="single_exam_type_no_subject">

             <input type="hidden" id="exam" name="exam_type[]" value="'.$exam_type.'">
             <input type="hidden" id="class_id" name="class_id" value="'.$class_id.'">
             <input type="hidden" id="stream_id" name="stream_id" value="'.$stream_id.'">
             <input type="hidden" id="subject_id" name="subject_id" value="'.$subject_id.'">
             <input type="hidden" id="report_name" name="report_name" value="'.$exam_type.'">
             <input type="hidden" id="academic_year_id" name="academic_year" value="'.$academic_year.'">
             <input type="hidden" id="report_name" name="report_name" value="'.$report_name.'">
             <input type="hidden" id="term_id" name="term_id" value="'.$semester.'">
             <input type="hidden" id="metadata" name="metadata" value='.$jt.'>

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
                             <th style="text-align:center" class="text-center" colspan="'.$colspan.'">
                             SUBJECTS  (x/ '.$examInfo->total_marks.')
                             </th>
                             <th style="text-align:center" rowspan="3"> AVG </th>
                             <th style="text-align:center" rowspan="3"> DIV </th>
                             <th style="text-align:center" rowspan="3"> POINTS </th>
                             <th style="text-align:center" rowspan="3"> C/T Comments </th>
                         </tr>
                         <tr>

                         '.$sbjcts_html.'


                         </tr>

                         <tr>


                         '.$subject_mark_grade_columns.'

                         <tr/>


                     </thead>

                     <tbody>


                     '.$tbody.'



                     </tbody>


                 </table>
                 </div>
                     </div>
                 </form>
             </div>



         </div>
     </div>
         ';

     $data['subjects'] = $subjects;


/* then we end it here */


        }

       $data['base_html'] = $base_html;


       return  response($data);

        }



      public function datatable(Request $req){

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

       $students = Student::join('school_classes','students.class_id','=','school_classes.id')
       ->leftjoin('streams','students.stream_id','=','streams.id')->orderBy('students.id')
       ->select('students.id','students.firstname','students.middlename','students.uuid','students.lastname')
       ->where('students.class_id',$class_id);


       if ($stream_id) {

        $students->where('students.stream_id', $stream_id);

    }


      if (count($exam_type) == 1) {

        $exam_type = $exam_type[0];

        $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';



        /* SUBJECT SELECTED && EXAM TYPE REPORT */

        if ($subject_id && $exam_type) {

            $results = Student::leftjoin('results','students.id','results.student_id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select('results.score','results.uuid','results.student_id','results.grade_group_id')
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

            ->addColumn('percentage',function($student) use($examInfo,$students_result){

                $score = $students_result[$student->id]['score'] ?? '-';

                if ($score !== '-') {
                    $percentage = $this->global->marksToPercentage($score, $examInfo);
                    return $percentage;
                } else {
                    return '-';
                }


            })

            ->addColumn('score', function($student) use ($students_result){

                return $students_result[$student->id]['score'] ?? '-';


            })


            ->addColumn('sn', function(){
                return '';
            })

            ->addColumn('admission_no', function($result){

                return $result->student_id;


            })

            ->editColumn('full_name', function($result){

                return $result->full_name;

            })

            ->addColumn('grade',function($student) use($examInfo,$elevel,$students_result){
                $score = $students_result[$student->id]['score'] ?? '-';

                if ($score !== '-') {

                    // return $student->grade_group_id;


                 return   $grade_collective = $this->global->getGrade($score,$elevel,$examInfo,$students_result[$student->id]['group_id'])['grade'];


                } else {
                    return '-';
                }

            })


            ->addColumn('remarks',function($student) use($examInfo,$elevel,$students_result){

                $score = $students_result[$student->id]['score'] ?? '-';
                if ($score !== '-') {
                    return   $grade_collective = $this->global->getGrade($score,$elevel,$examInfo,$students_result[$student->id]['group_id'])['remarks'];
                } else {
                    return '-';
                }


            })

            ->addColumn('admission_no', function($student){

                return $student->uuid;

            })


            ->addColumn('action',function($student) use($uuids){

               $uuid = $uuids[$student->id]['uuid'] ?? 0;
                // return ' <span>
                // <a href="javascript:void(0)" data-uuid="'.$uuid.'"  data-type="text" data-title="Update Marks" class="btn btn-custon-four btn-primary btn-xs editable editable-click"><i class="fa fa-edit"></i></a>
                // </span> ';

            })

            ->rawColumns(['action'])
            ->make();





            /* REPORT TWO     --------EXAM TYPE  && NO SUBJECT */

                }


    /* 2ND REPORT     EXAM TYPE WITH NO ACTUAL SUBJECT */


                elseif (!$subject_id && $exam_type) {


                    $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                    ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                    ->leftjoin('results', 'students.id', '=', 'results.student_id')
                    ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
                    ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
                    ->leftjoin('exams','results.exam_id','=','exams.id')
                    ->join('subjects', 'results.subject_id', 'subjects.id')
                    ->where('results.academic_year_id',$academic_year)
                    ->where('results.class_id',$class_id)
                    ->where('semesters.id',$semester)
                    ->select('results.score','results.class_id','results.grade_group_id', 'subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','subject_id','results.grade_group_id')

                    ->groupBy('students.id','subjects.id');


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

                 $code = strtolower( str_replace(' ','_',$subject['name']));

                $subjects_array[$code] = $code;

            }


            foreach ($data as $row) {

                $subject_name =  strtolower( str_replace(' ','_',$row->subject_name));
                $studentScores[$row->admission_no][$subject_name] = $row->score;
            }





       $datatable = DataTables::of($students)

        ->addColumn('full_name', function ($student) use($studentScores) {

          return  $student->firstname. ' '. $student->middlename. ' '.$student->lastname;

        })

        ->addColumn('avg', function($student) use($studentScores,$subjects_array) {


            $scores_array = [];

            foreach ($subjects_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }

            return $this->global->generateAvg($scores_array);

        })

        ->addColumn('remarks', function($student)  use($studentScores,$subjects_array){

            $scores_array = [];

            foreach ($subjects_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }

            return $this->global->generateAvg($scores_array);

        })

        ->addColumn('admission_no', function ($student) use($studentScores) {

            return  $student->uuid;

          })

          ->addColumn('grade', function ($student) use($studentScores,$subjects_array,$elevel) {


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

        ->addColumn('sn', function ($student) use($studentScores) {

            return  '';

          })

          ->addColumn('action', function($student) use($exam_type,$class_id,$semester,$stream_id,$academic_year,$elevel){


            return '<a  target="_blank" href="'.route('reports.single.exam.pdf',[$student->uuid,$exam_type,$class_id,$semester,$academic_year,$elevel,$stream_id]).'"  data-uuid="'.$student->uuid.'" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';

        });


        foreach ($subjects_array as $subject) {

            $datatable->addColumn($subject, function ($student) use ($subject,$studentScores) {

                return $studentScores[$student->id][$subject] ?? '-';
            });


        }




        return   $datatable->rawColumns(array_merge(['student_name','action'], $subjects_array))
        ->make(true);



                }


         /* REPORT --3.   SUBJECT ASSESSMENT PER TERM */

                 elseif ($subject_id && !$exam_type) {



                    $exams = Exam::all();

                    $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                    ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                    ->leftjoin('results', 'students.id', '=', 'results.student_id')
                    ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
                    ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
                    ->leftjoin('exams','results.exam_id','=','exams.id')
                    ->join('subjects', 'results.subject_id', 'subjects.id')
                    ->where('results.academic_year_id',$academic_year)
                    ->where('results.class_id',$class_id)
                    ->where('semesters.id',$semester)
                    ->select('results.score','results.class_id', 'subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','subject_id')
                    ->groupBy('students.id','subjects.id');


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

                 $code = strtolower( str_replace(' ','_',$exam['name']));

                $exams_array[$code] = $code;



            }

            foreach ($data as $row) {

                $subject_name =  strtolower( str_replace(' ','_',$row->subject_name));
                $studentScores[$row->admission_no][$subject_name] = $row->score;
            }

       $datatable = DataTables::of($students)

        ->addColumn('full_name', function ($student) use($studentScores) {

          return  $student->firstname. ' '. $student->middlename. ' '.$student->lastname;

        })

        ->addColumn('avg', function($student) use($studentScores,$exams_array) {


            $scores_array = [];

            foreach ($exams_array as $exam) {

                $score = $studentScores[$student->id][$exam] ??  0;

                    $scores_array[] = $score;

            }

            return $this->global->generateAvg($scores_array);

        })

        ->addColumn('remarks', function($student)  use($studentScores,$exams_array){

            $scores_array = [];

            foreach ($exams_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }

            return $this->global->generateAvg($scores_array);

        })

        ->addColumn('admission_no', function ($student) use($studentScores) {

            return  $student->uuid;

          })

          ->addColumn('grade', function ($student) use($studentScores,$exams_array,$elevel) {


            $scores_array = [];

            foreach ($exams_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }

            $avg = $this->global->generateAvg($scores_array);
            return $student->grade_group_id;

        return   $gradeCollection = $this->global->getGrade($avg,$elevel,$student->grade_group_id);
             return $gradeCollection;

          })

        ->addColumn('sn', function ($student) use($studentScores) {

            return  '';

          })

          ->addColumn('action', function($student) use($exam_type,$class_id,$semester,$stream_id,$academic_year,$elevel){


            return '<a  target="_blank" href="#"  data-uuid="'.$student->uuid.'" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';

        });


        foreach ($exams_array as $subject) {

            $datatable->addColumn($subject, function ($student) use ($subject,$studentScores) {

                return $studentScores[$student->id][$subject] ?? '-';
            });


        }




        return   $datatable->rawColumns(array_merge(['student_name','action'], $exams_array))
        ->make(true);







                 }


                 /* 4TH REPORT */

                 elseif ($class_id && !$subject_id && !$exam_type && $semester &&$academic_year) {

                    $data = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
                    ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
                    ->leftjoin('results', 'students.id', '=', 'results.student_id')
                    ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
                    ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
                    ->leftjoin('exams','results.exam_id','=','exams.id')
                    ->join('subjects', 'results.subject_id', 'subjects.id')
                    ->where('results.academic_year_id',$academic_year)
                    ->where('results.class_id',$class_id)
                    ->where('semesters.id',$semester)
                    ->select('results.score','results.class_id', 'subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','subject_id')
                    ->groupBy('students.id','subjects.id');


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

                 $code = strtolower( str_replace(' ','_',$subject['name']));

                $subjects_array[$code] = $code;



            }

            foreach ($data as $row) {

                $subject_name =  strtolower( str_replace(' ','_',$row->subject_name));
                $studentScores[$row->admission_no][$subject_name] = $row->score;
            }

       $datatable = DataTables::of($students)

        ->addColumn('full_name', function ($student) use($studentScores) {

          return  $student->firstname. ' '. $student->middlename. ' '.$student->lastname;

        })

        ->addColumn('avg', function($student) use($studentScores,$subjects_array) {


            $scores_array = [];

            foreach ($subjects_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }

            return $this->global->generateAvg($scores_array);

        })

        ->addColumn('remarks', function($student)  use($studentScores,$subjects_array){

            $scores_array = [];

            foreach ($subjects_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }

            return $this->global->generateAvg($scores_array);

        })

        ->addColumn('admission_no', function ($student) use($studentScores) {

            return  $student->uuid;

          })

          ->addColumn('gender',function(){
            return '';
          })
          ->addColumn('grade', function ($student) use($studentScores,$subjects_array,$elevel) {


            $scores_array = [];

            foreach ($subjects_array as $subject) {

                $score = $studentScores[$student->id][$subject] ??  0;

                    $scores_array[] = $score;

            }


            $avg = $this->global->generateAvg($scores_array);

           $gradeCollection = $this->global->getGrade($avg,$elevel);
             return $gradeCollection;

          })

        ->addColumn('sn', function ($student) use($studentScores) {

            return  '';

          })

          ->addColumn('action', function($student) use($exam_type,$class_id,$semester,$stream_id,$academic_year,$elevel){


            return '<a  target="_blank" href="#"  data-uuid="'.$student->uuid.'" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';

        });


        foreach ($subjects_array as $subject) {

            $datatable->addColumn($subject, function ($student) use ($subject,$studentScores) {

                return $studentScores[$student->id][$subject] ?? '-';
            });


        }




        return   $datatable->rawColumns(array_merge(['student_name','action'], $subjects_array))
        ->make(true);


                 }
      }

      elseif (count($exam_type) > 1) {
        /* tunaanzia apa */

       $datatable = DataTables::of($students)

       ->addColumn('full_name', function ($student) {

         return  $student->firstname. ' '. $student->middlename. ' '.$student->lastname;

       })

       ->addColumn('admission_no', function ($student) {

           return  $student->uuid;

         })

         ->addColumn('gender',function(){
           return '';
         })


       ->addColumn('sn', function ($student){

           return  '';

         })

         ->addColumn('action', function($student) use($exam_type,$class_id,$semester,$stream_id,$academic_year,$elevel){


           return '<a  target="_blank" href="'.route('reports.multiple.exam.pdf',
           [
            $student->uuid,
            http_build_query(['exam_type' => $exam_type]),
            $class_id,
            $semester,
            $academic_year,
            $elevel,
            $stream_id
           ]).'"  data-uuid="'.$student->uuid.'" class="btn btn-xs btn-warning"> <i class="fa fa-print"> </i> <a/>';

       });

       return   $datatable->rawColumns(array_merge(['student_name','action']))
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

        ){


    $data['subjects'] = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
                            ->select('subjects.name','subjects.id')->where('education_level_id',$elevel)->get();

      $results = Result::join('students','students.id','=','results.student_id')
                ->where('results.exam_id',$exam_type)
                ->where('results.class_id',$class_id)
                ->where('results.semester_id',$semester)
                ->where('students.uuid',$student_uuid)
                ->where('results.academic_year_id',$academic_year);

        if ($stream_id) {
            $results->where('results.stream_id',$stream_id);
        }

        $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

        $data['examInfo'] = $examInfo;
        $data['results'] = $results->get();
        $data['elevel'] = $elevel;

       $data['studentInfo'] = Student::leftjoin('school_classes','school_classes.id','=','students.class_id')
                                ->leftjoin('streams', 'streams.id','=','students.stream_id')
                               ->select('students.firstname','students.middlename','students.lastname','streams.name as stream_name','school_classes.name as class_name')
                               ->where('students.uuid',$student_uuid)
                               ->first();


        $pdf = PDF::loadView('results.reports.printouts.single_exam_type_report',$data);

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
        $stream_id=null
        )
        {


        $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';

        $students = SchoolClass::find($class_id)->students;

        $data['class'] = SchoolClass::find($class_id);
        $data['stream'] = '';
        $data['elevel'] = $elevel;

        $students = Student::join('school_classes','students.class_id','=','school_classes.id')
        ->leftjoin('streams','students.stream_id','=','streams.id')->orderBy('students.id')
        ->select('students.id','students.firstname','students.middlename','students.uuid','students.lastname')
        ->where('students.class_id',$class_id);

        if ($stream_id) {

            $students->where('students.stream_id', $stream_id);

        }

        $data['subject'] = Subject::find($subject_id);

            $results = Student::leftjoin('results','students.id','results.student_id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select('results.score','results.uuid','results.student_id','results.grade_group_id')
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

        $pdf = PDF::loadView('results.reports.printouts.single_subject_exam_type_checked',$data);
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

        ){

        $examtypeArray = [];


       parse_str($exam_type, $examtypeArray);


    $data['subjects'] = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
                            ->select('subjects.name','subjects.id')->where('education_level_id',$elevel)->get();


        $results = Result::join('students', 'students.id', '=', 'results.student_id')
        ->whereIn('results.exam_id', $examtypeArray['exam_type'])
        ->where('results.class_id', $class_id)
        ->where('results.semester_id', $semester)

        ->where('students.uuid', $student_uuid)
        ->where('results.academic_year_id', $academic_year);



        if ($stream_id) {
            $results->where('results.stream_id',$stream_id);
        }

        $exam_type ?  $examInfo = Exam::whereIn('exams.id',$examtypeArray['exam_type'])->where('exams.isCommutative',1)->get() : $examInfo = '';

        $data['examInfo'] = $examInfo;

        $data['results'] = $results->get();
        $data['elevel'] = $elevel;

       $data['studentInfo'] = Student::leftjoin('school_classes','school_classes.id','=','students.class_id')
                                ->leftjoin('streams', 'streams.id','=','students.stream_id')
                               ->select('students.firstname','students.middlename','students.lastname','streams.name as stream_name','school_classes.name as class_name')
                               ->where('students.uuid',$student_uuid)
                               ->first();


        $pdf = PDF::loadView('results.reports.printouts.multiple_exam_types_report',$data);

        // return view('results.reports.printouts.single_exam_type_report')->with($data);
        return $pdf->stream('students.pdf');


      }



/* HERE WE COME AMIGO */


    //   public function generateExamReport(Request $req){

    //     try {

    //         DB::beginTransaction();
    //         $class_id = $req->class_id;
    //         $stream = $req->stream_id;
    //         $metadata = decrypt($req->metadata);
    //         // return $req->all();
    //         $students = Student::where(['class_id'=>$class_id, 'stream_id'=>$stream])->get();

    //         $ct_comments = $req->ct_comment;

    //        $subject = $req->subject_id;
    //        $generated_report = GeneratedExamReport::create([
    //         'uuid'=>generateUuid(),
    //         'class_id'=>$req->class_id,
    //         'stream_id'=>$req->stream_id,
    //         'generated_by'=>auth()->user()->id,
    //         'exam_report_id'=>$req->report_name,
    //         'academic_year_id'=>$req->academic_year,
    //         'term_id'=>$req->term_id,
    //         'exam_type_combination'=>json_encode($req->exam_type),
    //         'subject_type_combination'=>json_encode($subject),
    //         'escalation_level_id'=>1
    //         ]);



    //         if ($generated_report) {

    //             foreach ($students as $key => $student) {

    //                 $student_id = $student->id;

    //                 if (isset($metadata[$student_id])) {
    //                     $studentData = $metadata[$student_id];
    //                    $student_gnr =  StudentResultReport::create([
    //                         'generated_exam_report_id'=>$generated_report->id,
    //                         'uuid'=>generateUuid(),
    //                         'student_id'=> $student_id,
    //                         'user_id'=>auth()->user()->id,
    //                         'metadata'=> json_encode($studentData),
    //                         'full_name'=> $student->firstname. ' '. $student->lastname,
    //                         // 'class_position'
    //                         // 'division'
    //                         // 'points'
    //                         // 'stream_position'

    //                      ]);


    //                      if ($ct_comments[$student_id] != 'Add Comment....') {

    //                         Comment::create(
    //                             [
    //                             'student_result_report_id'=>$student_gnr->id,
    //                             'predefined_comment_id'=>$ct_comments[$student_id],
    //                             'user_id'=>auth()->user()->id,

    //                             ]

    //                         );



    //                      }



    //                 }

    //             }




    //         }

    //         DB::commit();

    //         $data = ['status'=>'success','title'=> 'success','msg'=>'Report Generated'];
    //         return response($data);

    //         // return $req->all();
    //         // return response($generated_report);

    //     } catch (QueryException $e) {

    //         return $e->getMessage();

    //     }

    //   }


      public function generatedExamReportsIndex(){

        $data['activeTab'] = 'generatedReportsTab';
        return view('results.reports.generated.index')->with($data);


      }

      public function generatedExamReportsDatatable(Request $request){



            try {
                $generated_reports = GeneratedExamReport::select('academic_years.name as acdmc_name','school_classes.name as class_name','streams.name as stream_name',
                'semesters.name as semester_name','exam_reports.name as report_name','generated_exam_reports.escalation_level_id','generated_exam_reports.academic_year_id','escalation_levels.name as e_name',
                'generated_exam_reports.uuid as report_uuid')
        ->join('academic_years','academic_years.id','=','generated_exam_reports.academic_year_id')
        ->join('school_classes','school_classes.id','=','generated_exam_reports.class_id')
        ->join('streams','streams.id','=','generated_exam_reports.stream_id')
        ->join('semesters','generated_exam_reports.term_id','=','semesters.id')
        ->join('exam_reports','generated_exam_reports.exam_report_id','=','exam_reports.id')
        ->join('escalation_levels','escalation_levels.id','=','generated_exam_reports.escalation_level_id');

            if (auth()->user()->hasRole('Academic')) {

                $generated_reports->whereIn('generated_exam_reports.escalation_level_id', [2,3]);

            }

            if (auth()->user()->hasRole('Head Master')) {
                $generated_reports->whereIn('generated_exam_reports.escalation_level_id', [3]);

            }

              $search = $request->get('search');

            if(!empty($search)){

         //    $invoices = $invoices->where('account_student_details.first_name', 'like', '%'.$search.'%')
         //                       ->orWhere('account_student_details.middle_name', 'like', '%'.$search.'%')
         //                       ->orWhere('account_student_details.last_name', 'like', '%'.$search.'%')
         //                       ->orWhere('invoices.invoice_number', 'like', '%'.$search.'%');
         //    }

                 // $invoices = $invoices->groupBy('invoices.id');
            }

             return DataTables::of($generated_reports)

             ->editColumn('academic_year_id',function($report){

                 return $report->acdmc_name;

             })

             ->editColumn('term_id',function($report){

                return $report->semester_name;

            })

            ->editColumn('class_id',function($report){

                return $report->class_name;

            })

            ->editColumn('stream_id',function($report){

                return $report->stream_name;

            })

            ->editColumn('exam_report_id',function($report){


                return $report->report_name;

            })

            ->editColumn('escalation_level_id',function($report){

                return  '<span style="padding: 2px 5px; border-radius: 5px; color: darkblue;
                border: 1px dotted rgba(0, 0, 139, 0.596);" class=" btn-success material-symbols-outlined">
                '.$report->e_name.'
                </span>';

            })

             ->editColumn('created_by',function($report){

                 return 'admin';
             })

             ->addColumn('action',function($report){
                 return '<span> <a data-uuid="'.$report->uuid.'" href="'.route('results.reports.generated.reports.view.indrive',$report->report_uuid).'" type="button" class="btn btn-custon-four btn-warning btn-xs preview"><i class="fa fa-eye"></i></a>
             </span>';

             })

           ->rawColumns(['action','escalation_level_id'])
           ->make();

            } catch (QueryException $e) {

               return $e->getMessage();

            }


      }


      public function oneLevelUp(Request $req){

        try {

            DB::beginTransaction();

            // return $req->all();
            $signature = $req->signature;
            EscalationLevel::create(['uuid'=>generateUuid(),'name'=>'Published']);
            $uuid = $req->uuid;
            $report = GeneratedExamReport::where('uuid',$uuid)->first();
            $new_level =  $report->escalation_level_id + 1;
            $gn = GeneratedExamReport::where('uuid',$uuid)->first();
            $attempt = GeneratedExamReport::where('uuid',$uuid)->update(['escalation_level_id'=>$new_level]);
            DB::commit();

           if ($attempt) {

            $data = ['msg'=>'Report Escalated', 'title'=>'success'];

            if (auth()->user()->hasRole('Head Master')) {

                $gn->update(['is_published'=>1,'include_signature'=> $signature]);

                $data = ['msg'=>'Report Saved & Published', 'title'=>'success'];

            }

            return response($data);
           }
           $data = ['msg'=>'OOPS... Something Went Wrong...', 'title'=>'info'];
           return response()->json($data);

        }catch (QueryException $e) {

            return $e->getMessage();

        }


      }



      public function generatedExamReportViewIndrive($uuid){

       $data['generated_exam_report'] = $generated_exam_report = GeneratedExamReport::where('uuid',$uuid)->first();

       $exam_type_combination = $generated_exam_report->exam_type_combination;

       $class_id = $generated_exam_report->class_id;
       $semester = $generated_exam_report->term_id;
       $stream_id = $generated_exam_report->stream_id;
       $subject_id = $generated_exam_report->subject_id;
       $academic_year = $generated_exam_report->academic_year_id;
       $elevel = SchoolClass::find($class_id)->education_level_id;

       $data['predefined_comments'] = PredefinedComment::all();


       if ($exam_type_combination) {

        $count  = count(json_decode($exam_type_combination));

         $generated_exam_report->subject_type_combination;

         if ($count == 1 ) {

            $exam_type = json_decode($exam_type_combination)[0];

            $exam_type ?  $examInfo = Exam::find($exam_type) : $examInfo = '';
            $data['examInfo'] = $examInfo;



        if ($count == 1 &&  $generated_exam_report->subject_type_combination != null) {


            /* DRAFT FIRST */

           $subjects = Subject::join('subject_education_levels','subjects.id','=','subject_education_levels.subject_id')
           ->select('subjects.id as subject_id','subjects.uuid as sbjct_uuid','subjects.code as sbjct_code','subjects.name as sbjct_name')
                                ->where('education_level_id',$elevel)->get();

             $data['subjects'] = $subjects;

            /* lets try this out */



   $data['matokeo'] = StudentResultReport::select(
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

            /* end */

        $students = Student::join('school_classes','students.class_id','=','school_classes.id')
        ->leftjoin('streams','students.stream_id','=','streams.id')->orderBy('students.id')
        ->select('students.id','students.firstname','students.middlename','students.uuid','students.lastname')
        ->where('students.class_id',$class_id);

        if ($stream_id) {

         $students->where('students.stream_id', $stream_id);

     }

     $data['trigger_hm'] = count(Comment::whereNotIn('type', ['HM'])->get());

     $data['students'] = $students;

     $details = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
     ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
     ->leftjoin('results', 'students.id', '=', 'results.student_id')
     ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
     ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
     ->leftjoin('exams','results.exam_id','=','exams.id')
     ->join('subjects', 'results.subject_id', 'subjects.id')
     ->where('results.academic_year_id',$academic_year)
     ->where('results.class_id',$class_id)
     ->where('semesters.id',$semester)
     ->select('results.score','results.class_id','results.grade_group_id','subjects.name as subject_name', 'students.id as admission_no','exam_id','results.stream_id','results.subject_id','results.grade_group_id')

     ->groupBy('students.id','subjects.id');


     if ($exam_type) {
         $details->where('results.exam_id', $exam_type);
     }
     if ($stream_id) {
         $details->where('results.stream_id', $stream_id);
     }
     if ($subject_id) {
         $details->where('results.subject_id', $subject_id);
     }

     $data['objective_data'] = $details = $details->get();
     $data['predefined_comments'] = PredefinedComment::all();

     $kmc = array();

     foreach ($details as $row) {

         $kmc[$row->admission_no][] = ['student_id'=>$row->admission_no, 'subject_id'=>$row->subject_id, 'score'=>$row->score,'group_id'=>$row->grade_group_id];

     }
    //  $predefined_comments = PredefinedComment::all();






        return view('results.reports.generated.indrive_single_exam_all_subjects')->with($data);


                /* FROM THE DATATABLE */






     foreach ($subjects as $key => $subject) {

          $code = strtolower( str_replace(' ','_',$subject['subject_name']));

         $subjects_array[$code] = $code;

     }



    $kmc = array();

     foreach ($data as $row) {

         $kmc[$row->admission_no][] = ['student_id'=>$row->admission_no, 'subject_id'=>$row->subject_id, 'score'=>$row->score,'group_id'=>$row->grade_group_id];

     }
     $predefined_comments = PredefinedComment::all(); 


     $options = '<option> Add Comment.... </option>';
     foreach ($predefined_comments as $key => $pre) {
       $options .= '<option value="'.$pre->id.'">'.$pre->comment.'</option>';
     }

    //  return $kmc[89];


                $tbody = '';
                // return $students;
                $metadata = array();
                foreach ($students->get() as $key => $student) {

                    $full_name = $student->firstname.' '.$student->lastname;

                   $tbody.= '  <tr>

                   <td> '.++$key.' </td>
                   <td>'.$student->uuid.' </td>
                   <td> '.$full_name.'</td>';
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
                            $score_percent =   $this->global->marksToPercentage($km['score'],$examInfo);
                            $grade = $this->global->getGrade($score,$elevel,$examInfo,$group_id)['grade'];

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





                    $tbody.='
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

                        <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">
                        '.$score_percent.'
                        </td>
                        <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center"> '.$grade.'</td>';

                }

                $tbody.='


                   <td> </td>
                   <td> </td>
                   <td> </td>
                   <td style="min-width: 24rem;"> <div class="form-group">
                   <select name="ct_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </div></td>
                   <td style="min-width: 24rem;"> <select name="hm_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </td>
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

                 $jt = bcrypt($metadata);

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

                        <input type="hidden" id="exam" name="exam_type[]" value="'.$exam_type.'">
                        <input type="hidden" id="class_id" name="class_id" value="'.$class_id.'">
                        <input type="hidden" id="stream_id" name="stream_id" value="'.$stream_id.'">
                        <input type="hidden" id="subject_id" name="subject_id" value="'.$subject_id.'">
                        <input type="hidden" id="report_name" name="report_name" value="'.$exam_type.'">
                        <input type="hidden" id="academic_year_id" name="academic_year" value="'.$academic_year.'">
                        <input type="hidden" id="report_name" name="report_name" value="'.$report_name.'">
                        <input type="hidden" id="term_id" name="term_id" value="'.$semester.'">
                        <input type="hidden" id="metadata" name="metadata" value="'.$jt.'">

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
                                        <th style="text-align:center" class="text-center" colspan="'.$colspan.'">
                                        SUBJECTS  (x/ '.$examInfo->total_marks.')
                                        </th>
                                        <th style="text-align:center" rowspan="3"> AVG </th>
                                        <th style="text-align:center" rowspan="3"> DIV </th>
                                        <th style="text-align:center" rowspan="3"> POINTS </th>
                                        <th style="text-align:center" rowspan="3"> C/T Comments </th>
                                        <th style="text-align:center" rowspan="3"> H/M comments </th>
                                    </tr>
                                    <tr>

                                    '.$sbjcts_html.'


                                    </tr>

                                    <tr>


                                    '.$subject_mark_grade_columns.'

                                    <tr/>


                                </thead>

                                <tbody>


                                '.$tbody.'



                                </tbody>


                            </table>
                            </div>
                                </div>
                            </form>
                        </div>



                    </div>
                </div>
                    ';

                $data['subjects'] = $subjects;

            /* DRAFT EXIT */


            return view('results.reports.generated.indrive_single_exam_all_subjects');

        }




       }


        return $generated_exam_report;


      }

    }



/* COMMENT HM UPDATE */

public function hmCommentUpdate(Request $req){

try {

   $gnr = StudentResultReport::where('uuid', $req->report_uuid)->first();
    if($req->selected_value){
        DB::beginTransaction();

        $comment = Comment::create(
            [
                'student_result_report_id' => $gnr->id,
                'hm_comment' => $req->selected_value,

            ]);

        DB::commit();
        if ($comment) {
            $data = ['msg'=>'success','title'=>'success'];
            return response()->json($data);
        }
        $data = ['msg'=>'Fail','title'=>'Ooops... Something wrong'];
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



