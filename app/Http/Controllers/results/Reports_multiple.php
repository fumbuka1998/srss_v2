/* MULTIPLE EXAM TYPES SELECTED */
/* **************************************************************************************************************************************************************** */
/* tunaanzia hapa */

 
$predefined_comments = PredefinedComment::all();
 $options = '<option> Add Comment.... </option>';
 foreach ($predefined_comments as $key => $pre) {
   $options .= '<option value="'.$pre->id.'">'.$pre->comment.'</option>';
 }


$sbjct_columns = [];
$sbjcts_html = '';
$colspan = 0;

 // return $examInfo->total_marks;

 $subject_mark_grade_columns = '';

 // return $subjects;
 $exam_types_html = '';
 $exam_type_columns = array();

 $data['examodel'] = Exam::class;

  $data['exam_type'] = $exam_type;


     foreach ($subjects as $key => $subject) {
        $code  =   strtolower( str_replace(' ','_',$subject->sbjct_name));
        $subjct_span = 0;
        foreach ($exam_type as $key => $exam) {
            $exam_model = Exam::find($exam);
            $subjct_span +=1;

            $exam_type_columns[] = [
                'subject_id'=> $subject->subject_id,
                'exam'=> $exam_model,
            ];

             $exam_types_html.='
             <th style="text-align:center" data-subject_id="'.$subject->subject_id.'" data-exam-id="'.$exam_model->id.'" class="text-center">'.$exam_model->code.'/'.$exam_model->total_marks.'</th>';

          }
          $exam_types_html.= '<th style="text-align:center" data-subject_id=""  class="text-center">AVG %</th> <th style="text-align:center" data-subject_id=""  class="text-center">GRADE</th> <th style="text-align:center" data-subject_id=""  class="text-center">POINTS</th>';
          $subjct_span += 3;

        $sbjcts_html.= '<th style="text-align:center" colspan="'.$subjct_span.'" data-subject_uuid="'.$subject->uuid.'">'.$subject->sbjct_code.' </th>';
        $sbjct_columns[] = [
            'data'=> $subject->sbjct_code,
            'name'=> $code,
            'subject_id'=> $subject->subject_id,
        ];
        $colspan +=1;
    }

    $data['subjects'] = $subjects;


    $data['students'] = $students;

    $exams = Exam::whereIn('id',$exam_type)->get();

    $total_score_exams = 0;
    foreach ($exams as $key => $exam) {

        $total_score_exams+= $exam->total_marks;
    }

    $subject_results_arr = [];
    $sum=0;



    $pre_info = Student::join('school_classes', 'school_classes.id', '=', 'students.class_id')
    ->leftjoin('streams', 'school_classes.id', '=', 'streams.class_id')
    ->leftjoin('results', 'students.id', '=', 'results.student_id')
    ->leftjoin('academic_years','academic_years.id','=','results.academic_year_id')
    ->leftjoin('semesters','academic_years.id','=','semesters.academic_year_id')
    ->leftjoin('exams','results.exam_id','=','exams.id')
    ->leftjoin('subjects', 'results.subject_id', 'subjects.id')
    ->where('results.academic_year_id',$academic_year)
    ->where('results.class_id',$class_id)
    ->whereIn('results.exam_id', $exam_type)
    ->where('semesters.id',$semester)
    ->select('results.score','results.class_id','results.grade_group_id','results.student_id','subjects.name as subject_name','subjects.subject_type', 'results.full_name','students.id', 'students.id as admission_no','exam_id','results.stream_id','results.subject_id','results.grade_group_id')
    ->where('results.status','COMPLETED');

    if ($stream_id) {
        $pre_info->where('results.stream_id', $stream_id);
    }
    if ($subject_id) {
        $pre_info->where('results.subject_id', $subject_id);
    }


    foreach($results as $result){

        // return $result;

        if(isset($result)){


            $score = isset($result->score) ? $result->score : 0 ;
            if ($score != '-') {

                $sum += intval($score);

            }

           $average = $this->getAverage($result->admission_no,$result->subject_id,$exam_type,$result->grade_group_id,$elevel,$pre_info)['average'];
        //
           $grade = $this->getAverage($result->admission_no,$result->subject_id,$exam_type,$result->grade_group_id,$elevel,$pre_info)['grade'];
           $remarks = $this->getAverage($result->admission_no,$result->subject_id,$exam_type,$result->grade_group_id,$elevel,$pre_info)['remarks'];
           $points = $this->getAverage($result->admission_no,$result->subject_id,$exam_type,$result->grade_group_id,$elevel,$pre_info)['points'];

            $subject_results_arr[$result->admission_no][$result->subject_id][$result->exam_id] = $score;
            $subject_results_arr[$result->admission_no][$result->subject_id]['AVG'] = $average;
            $subject_results_arr[$result->admission_no][$result->subject_id]['GRADE'] = $grade;
            $subject_results_arr[$result->admission_no][$result->subject_id]['POINT'] = $points;
            $subject_results_arr[$result->admission_no][$result->subject_id]['REMARKS'] = $remarks;
            $subject_results_arr[$result->admission_no]['grade_group'] = $result->grade_group_id;


        }



    }


    $table_items = [];


    foreach($students as $student){

        $total_avg = 0;
        $total_points = 0;
        if (isset($subject_results_arr[$student->id]) ) {

         $fetch = $subject_results_arr[$student->id];

            for ($k=0; $k < count($sbjct_columns); $k++) {
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
               $total_points += $point;
            }
        }
}

        $avg = round($total_avg/($total_score_exams * count($sbjct_columns)) * 100);
        $group_id = $fetch['grade_group'];

        $grade = $this->global->getAverageGrade($avg,$elevel,$group_id)['grade'];
        $points = $this->global->getAverageGrade($avg,$elevel,$group_id)['points'];
        $remarks =  $this->global->getAverageGrade($avg,$elevel,$group_id)['remarks'];

        $table_items[$student->id] = [
            'admission_number' => $student->id,
            'full_name' => $student->full_name,
            'results' => isset($subject_results_arr[$student->id]) ? $subject_results_arr[$student->id] : [],
            'avg' => $avg,
            'grade'=>$grade,
            'division'=>'',
            'remarks'=>$remarks,
            'points'=>$total_points
        ];
    }

   $jt = encrypt($table_items);

    $indexToInsert = 2;
   $colspan = $colspan  * $subjct_span;


    $report_name = $req->report_name;
    $exam_type = encrypt($exam_type);
    $base_html ='
<div class="row">
<div class="col-lg-12" style="margin-bottom: 5rem">
<div style="margin-top:2rem" class=" in animated zoomInDown active">
<div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
<div class="col-md-12" style="margin-bottom: 1rem">
<span style="float:right">
<a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_dynamic_multiple_report btn btn-success btn-sm"> <i class="fa-solid fa-file-lines"></i> Generate  Reportss </a>
<a href="javascript:void(0)" title="pdf"  style="color:white; display:none"  class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
</span>

</div>

    <div class="col-md-12">

    <form id="generate_dynamic_multiple_report">

    <input type="hidden" id="exam" name="exam_type[]" value='.$exam_type.'>
    <input type="hidden" id="class_id" name="class_id" value="'.$class_id.'">
    <input type="hidden" id="stream_id" name="stream_id" value="'.$stream_id.'">
    <input type="hidden" id="subject_id" name="subject_id" value="'.$req->subject.'">
    <input type="hidden" id="report_name" name="report_name" value="'.$report_name.'">
    <input type="hidden" id="academic_year_id" name="academic_year" value="'.$academic_year.'">
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
            SUBJECTS
            </th>
            <th style="text-align:center" rowspan="3"> AVG </th>
            <th style="text-align:center" rowspan="3"> GRD </th>
            <th style="text-align:center" rowspan="3"> DIV </th>
            <th style="text-align:center" rowspan="3"> POINTS </th>
            <th style="text-align:center" rowspan="3"> C/T Comments </th>
        </tr>
        <tr>

        '.$sbjcts_html.'


        </tr>';
        $tr = '';
        for ($i=0; $i<count($sbjct_columns);  $i++) {
            foreach ($exam_type_columns as $key => $coltype) {
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
    $key = 0;

        foreach ($students as $key => $student) {
            $avg = 0;
            $checkpoints = 0;

                  $tbody.= ' <tr>
                        <td> '.++$key.' </td>
                        <td>'.$table_items[$student->id]['admission_number'].' </td>
                        <td>'.$table_items[$student->id]['full_name'].'</td>';
                        // return $table_items[$student->id]['results'];
                        if (count($table_items[$student->id]['results'])) {
                            for ($k=0; $k < count($sbjct_columns); $k++) {

                            $types_ex = $req->exam_type;
                            foreach ($types_ex as $key => $column) {
                                $score = '-';
                                $check['AVG'] = '-';
                                $check['POINT'] = '-';
                                $check['GRADE'] = '-';
                                if (isset($table_items[$student->id]['results'][$sbjct_columns[$k]['subject_id']])) {
                                    $check = $table_items[$student->id]['results'][$sbjct_columns[$k]['subject_id']];
                                    if (isset($check[$column])) {
                                      $score =  $check[$column];
                                }
                                }
                                $tbody.= '<td> '.$score.' </td>';

                               }



                               $avg += floatval($check['AVG']);
                               $checkpoints += floatval($check['POINT']);

                               $tbody.= '<td> '.$check['AVG'].' </td> <td> '.$check['GRADE'].' </td> <td> '.$check['POINT'].' </td>';
                                }

                            $tbody.=' <td>  '.$table_items[$student->id]['avg'].' </td> <td> '.$table_items[$student->id]['grade'].' </td> <td> '.$table_items[$student->id]['division'].' </td>  <td> '.$table_items[$student->id]['points'].' </td> <td>  <select name="ct_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </div> </td>   </tr>';
                        }




        }


    $base_html .= $tbody.'</tbody>
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

     $students_meta = isset(json_decode($student->metadata)->results) ? json_decode($student->metadata)->results : array();




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


private function processGradeAveragesIndrive($studentsData){


    $subjectAverages = [];

    foreach ($studentsData as $studentId => $student) {

        $students_meta = isset(json_decode($student->metadata)->results)  ?  json_decode($student->metadata)->results : array();

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


private function processGradeAverages($studentsData){


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


public function getResultForStudentSubjectExam($student_id, $subject_id, $exam_type_id,$data){


$rows = '';
foreach ($data as $key => $dt) {

if ($student_id == $dt->admission_no && $subject_id == $dt->subject_id && $exam_type_id == $dt->exam_id  ) {
    $rows .=   '<td  data-exam_id="'.$dt->exam_id.'">exam-id '.$dt->exam_id.' - subjct_id '.$dt->subject_id.'- '.$dt->score.' </td> ';
}

}
return $rows;
}

function getAverage($admission_no,$subject_id,$exam_type,$grade_group_id,$elevel,$data) {
    $sum = 0;
    $total_scores = 0;
    for ($i=0; $i <count($exam_type); $i++) {
        $exam = Exam::find($exam_type[$i]);
        $sum +=  $exam->total_marks;

        $exam_id = intval($exam_type[$i]);

        $model = with(clone $data)->where('results.student_id',$admission_no)->where('results.subject_id',$subject_id)->where('results.exam_id',$exam_id)->groupBy('subjects.id')->first();
        if ($model) {

             $score = floatval($model->score);

            if($score != 'x' || $score != 's'){

                $total_scores += ($score);


             }

        }


    }

 $average = round((floatval($total_scores)/floatval($sum) * 100));
 $average ? $average  : '-';
 $grade = $this->global->getAverageGrade($average,$elevel,$grade_group_id)['grade'];
 $points = $this->global->getAverageGrade($average,$elevel,$grade_group_id)['points'];
 $remarks = $this->global->getAverageGrade($average,$elevel,$grade_group_id)['remarks'];
$entry['average'] = $average;
$entry['grade'] = $grade;
$entry['points'] = $points;
$entry['remarks'] = $remarks;
return $entry;

}
