<?php

namespace App\Http\Controllers\academic\ca;

use App\Exports\CharacterAssessmentTemplateExport;
use App\Exports\CharacterAssessmentValidationError;
use App\Http\Controllers\Controller;
use App\Imports\CharacterAssessmentImport;
use App\Models\AcademicYear;
use App\Models\CharacterAssessment;
use App\Models\CharacterAssessmentReport;
use App\Models\Exam;
use App\Models\ExamReport;
use App\Models\ExamSchedule;
use App\Models\GeneratedExamReport;
use App\Models\GradeGroup;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ValidationError;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use Yajra\DataTables\DataTables;

class CharacterAssessmentsAllocationController extends Controller
{

    public $global;
    public function __construct()
    {
        $this->global = new GlobalHelpers();
    }


    public function index($uuid)
    {


        $data['activeTab'] = 'caTab';
        $data['uuid'] = $uuid;
        $data['activeRadio'] = 'ca';

        $data['generated_exam_report'] = $generated_report = GeneratedExamReport::where('uuid', $uuid)->first();
        $data['acdmc_year'] = AcademicYear::find($generated_report->academic_year_id);
        //    $data['exam'] = Exam::find($exschecdule->exam_id);
        $data['semester'] = Semester::find($generated_report->term_id);
        $data['stream_id'] = $stream_id = $generated_report->stream_id;
        $data['clasxs_id'] = $class_id = $generated_report->class_id;
        $data['class_model'] = SchoolClass::find($class_id);
        $data['stream_model'] = Stream::find($stream_id);
        $data['report_type'] = ExamReport::find($generated_report->exam_report_id);

        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();




        // $data[' '] = '  ';
        return view('results.reports.generated.character_assessments.table')->with($data);
    }




    public function datatable(Request $req, $uuid)
    {
        try {

            $generated_exam_report = GeneratedExamReport::where('uuid', $uuid)->first();

            $character_reports = CharacterAssessmentReport::where('generated_exam_report_id', $generated_exam_report->id)->get();

            $character_data = array();
            // foreach ($character_reports as $key => $crp) {

            //     $student = Student::find($crp->student_id);

            //     $character_data[$crp->student_id]['code_'.$crp->code] = $crp->grade;
            //     $character_data[$crp->student_id]['full_name'] = $student->full_name;
            //     $character_data[$crp->student_id]['admission_no'] = $student->admission_no;
            //     $character_data[$crp->student_id]['student_id'] = $crp->student_id;
            //     $character_data[$crp->student_id]['generated_exam_report_id'] = $crp->generated_exam_report_id;

            // }

            foreach ($character_reports as $crp) {

                //  return $crp;

                $student = Student::find($crp->student_id);

                $character_data[$crp->student_id]['code_' . $crp->code] = $crp->grade;
                $character_data[$crp->student_id]['full_name'] = $student->full_name;
                $character_data[$crp->student_id]['admission_no'] = $student->admission_no;
                $character_data[$crp->student_id]['student_id'] = $crp->student_id;
                $character_data[$crp->student_id]['generated_exam_report_id'] = $crp->generated_exam_report_id;
                $character_data[$crp->student_id]['attendance'] = $crp->attendance;
                $character_data[$crp->student_id]['late'] = $crp->late;
            }


            //  return $character_data;

            /* tunaanzia apa */

            $datatable = DataTables::of($character_data)

                ->editColumn('full_name', function ($data) {

                    return $data['full_name'];
                })

                ->editColumn('admission_number', function ($data) {

                    return $data['admission_no'];
                })


                ->addColumn(
                    'code_901',
                    function ($data) {

                        return $data['code_901'];
                    }

                )
                ->addColumn(
                    'code_902',
                    function ($report) {

                        return $report['code_902'];
                    }

                )
                ->addColumn(
                    'code_903',
                    function ($report) {

                        return $report['code_903'];
                    }

                )
                ->addColumn(
                    'code_904',
                    function ($report) {

                        return $report['code_904'];
                    }

                )
                ->addColumn(
                    'code_905',
                    function ($report) {

                        return $report['code_905'];
                    }

                )
                ->addColumn(
                    'code_906',
                    function ($report) {

                        return $report['code_906'];
                    }

                )
                ->addColumn(
                    'code_907',
                    function ($report) {

                        return $report['code_907'];
                    }

                )
                ->addColumn(
                    'code_908',
                    function ($report) {

                        return $report['code_908'];
                    }

                )
                ->addColumn(
                    'code_909',
                    function ($report) {

                        return  $report['code_909'];
                    }

                )
                ->addColumn(
                    'code_910',
                    function ($report) {

                        return  $report['code_910'];
                    }

                )
                ->addColumn(
                    'code_911',
                    function ($report) {

                        return  $report['code_911'];
                    }
                )
                ->addColumn(
                    'attendance',
                    function ($report) {

                        return  $report['attendance'];
                    }
                )

                ->addColumn(
                    'late',
                    function ($report) {

                        return  $report['late'];
                    }
                )

                ->addColumn('action', function ($report) {

                    return '<a href="javascript:void(0)" data-student_id="' . $report['student_id'] . '" data-id="' . $report['generated_exam_report_id'] . '" class="btn btn-sm edit btn-info" > <i class="fa fa-edit"> </i> <a/>';
                });

            return   $datatable->rawColumns(array_merge(['student_name', 'action']))
                ->make(true);

            /* we end here */
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function generateTemplate($uuid)
    {

        $generated = GeneratedExamReport::where('uuid', $uuid)->first();

        // character.assessments.excel.template

        $students = Student::where(['class_id' => $generated->class_id, 'stream_id' => $generated->stream_id])->select('students.id', 'students.firstname', 'students.middlename', 'students.lastname', 'students.stream_id', 'students.class_id');
        $classname = SchoolClass::find($generated->class_id)->name;
        $streamname = Stream::find($generated->stream_id)->name;
        $data['students'] = $students = $students->get();


        $filename = $classname . ' ' . $streamname . ' ' . 'template';
        $data['assessments'] = $assessments = CharacterAssessment::all();
        $data['colspan'] = $assessments->count();

        return FacadesExcel::download(new CharacterAssessmentTemplateExport($data), '' . $filename . '.xlsx');
    }






    public function create($uuid)
    {


        $data['activeTab'] = 'caTab';
        $data['uuid'] = $uuid;
        $data['activeRadio'] = 'ca';

        $generated_report = GeneratedExamReport::where('uuid', $uuid)->first();
        $data['acdmc_year'] = AcademicYear::find($generated_report->academic_year_id);
        //    $data['exam'] = Exam::find($exschecdule->exam_id);
        $data['semester'] = Semester::find($generated_report->term_id);
        $data['stream_id'] = $stream_id = $generated_report->stream_id;
        $data['clasxs_id'] = $class_id = $generated_report->class_id;
        $data['class_model'] = SchoolClass::find($class_id);
        $data['stream_model'] = Stream::find($stream_id);
        $data['report_type'] = ExamReport::find($generated_report->exam_report_id);

        $data['classes'] = SchoolClass::all();
        $data['subjects'] = Subject::all();
        $data['exams'] = Exam::all();
        $data['streams'] = Stream::all();
        $data['academic_years'] = AcademicYear::all();
        $data['assessments'] = CharacterAssessment::all();

        return view('results.reports.generated.character_assessments.index')->with($data);
    }



    public function edit(Request $req, $id)
    {

        // return $req->all();
        // return $id;
        CharacterAssessmentReport::find($id);
        $character_reports = CharacterAssessmentReport::where('generated_exam_report_id', 69)
            ->where('student_id', $req->student_id)
            ->get();
        $student_id = 0;

        $character_data = array();

        foreach ($character_reports as $key => $crp) {

            $student = Student::find($crp->student_id);
            $character_data[$crp->student_id]['code_' . $crp->code] = $crp->grade;
            $character_data[$crp->student_id]['full_name'] = $student->full_name;
            $character_data[$crp->student_id]['admission_no'] = $student->admission_no;
            $character_data[$crp->student_id]['student_id'] = $crp->student_id;
            $character_data[$crp->student_id]['generated_exam_report_id'] = $crp->generated_exam_report_id;
            $student_id = $crp->student_id;
        }
        $data = ['student_id' => $student_id, 'character_data' => $character_data];
        return response($data);
    }



    public function characterAssessmentImport(Request $request, $uuid)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $generated =  GeneratedExamReport::where('uuid', $uuid)->first();
        $success = 0;
        $failed = 0;

        $ca_upload_fail_validation = [];
        $import = new CharacterAssessmentImport();
        $filePath = $request->file->path();
        $extension = $request->file->getClientOriginalExtension();
        $xlsx = Excel::XLSX;
        $xls = Excel::XLS;
        $csv = Excel::CSV;
        $formats = [$xlsx, $xls, $csv];

        foreach ($formats as $key => $format) {
            if (strtolower($format) == strtolower($extension)) {
                $rows = \Maatwebsite\Excel\Facades\Excel::toArray($import, $filePath, null, $format)[0];
            }
        }
        //   return $rows;

        try {

            // return $rows['days_late'];
            ini_set('max_execution_time', 0);

            $codes = ['901', '902', '903', '904', '905', '906', '907', '908', '909', '910', '911'];

            //initiate the most important thing to take place here i

            if (count($rows)) {

                foreach ($rows as $index => $row) {

                    $validation_description = '';
                    $row_validation = [
                        'full_name' => false,
                        '901' => false,
                        '902' => false,
                        '903' => false,
                        '904' => false,
                        '905' => false,
                        '906' => false,
                        '907' => false,
                        '908' => false,
                        '909' => false,
                        '910' => false,
                        '911' => false,
                        'admission_number' => false,
                        'attendance' => false,
                        'late' => false,
                    ];

                    //  return $row['days_late'];
                    /* commented for now */
                    if (isset($row['admission_number'])) {

                        $row_validation['admission_number'] = true;
                        $student_details['admission_number'] = $row['admission_number'];
                    } else {

                        $validation_description .= 'Admission Number Cant Be Empty.';
                    }

                    if (isset($row['attendance'])) {

                        $row_validation['attendance'] = true;
                        $student_details['attendance'] = $row['attendance'];
                    } else {

                        $validation_description .= 'Attendance Cant Be Empty.';
                    }
                    if (isset($row['late'])) {

                        $row_validation['late'] = true;
                        $student_details['late'] = $row['late'];
                    } else {

                        $validation_description .= 'Late Cant Be Empty.';
                    }

                    /* FIRST NAME VALIDATION */

                    if (isset($row['full_name'])) {

                        $validation = $this->studentNameValidation($row['full_name']);
                        if ($validation['status']) {
                            $row_validation['full_name'] = true;
                            $student_details['full_name'] = $row['full_name'];
                        } else {

                            $validation_description .= $validation['msg'];
                        }
                    } else {
                        $validation_description .= 'Name Cant Be Empty.';
                    }

                    if (isset($row['901'])) {

                        $validation = $this->characterCodeValidation($row['901']);
                        if ($validation) {
                            $student_details['901'] = $row['901'];
                            $row_validation['901'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['901'] . '"';
                        }
                    }

                    if (isset($row['902'])) {

                        $validation = $this->characterCodeValidation($row['902']);
                        if ($validation) {
                            $student_details['902'] = $row['902'];
                            $row_validation['902'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['902'] . '"';
                        }
                    }

                    if (isset($row['903'])) {

                        $validation = $this->characterCodeValidation($row['903']);
                        if ($validation) {
                            $student_details['903'] = $row['903'];
                            $row_validation['903'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['903'] . '"';
                        }
                    }

                    if (isset($row['904'])) {

                        $validation = $this->characterCodeValidation($row['904']);
                        if ($validation) {
                            $student_details['904'] = $row['904'];
                            $row_validation['904'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['904'] . '"';
                        }
                    }

                    if (isset($row['905'])) {

                        $validation = $this->characterCodeValidation($row['905']);
                        if ($validation) {
                            $student_details['905'] = $row['905'];
                            $row_validation['905'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['905'] . '"';
                        }
                    }

                    if (isset($row['906'])) {

                        $validation = $this->characterCodeValidation($row['906']);
                        if ($validation) {
                            $student_details['906'] = $row['906'];
                            $row_validation['906'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['906'] . '"';
                        }
                    }

                    if (isset($row['907'])) {

                        $validation = $this->characterCodeValidation($row['907']);
                        if ($validation) {
                            $student_details['907'] = $row['907'];
                            $row_validation['907'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['907'] . '"';
                        }
                    }

                    if (isset($row['908'])) {

                        $validation = $this->characterCodeValidation($row['908']);
                        if ($validation) {
                            $student_details['908'] = $row['908'];
                            $row_validation['908'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['908'] . '"';
                        }
                    }

                    if (isset($row['909'])) {

                        $validation = $this->characterCodeValidation($row['909']);
                        if ($validation) {
                            $student_details['909'] = $row['909'];
                            $row_validation['909'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['909'] . '"';
                        }
                    }

                    if (isset($row['910'])) {

                        $validation = $this->characterCodeValidation($row['910']);
                        if ($validation) {
                            $student_details['910'] = $row['910'];
                            $row_validation['910'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['910'] . '"';
                        }
                    }

                    if (isset($row['911'])) {

                        $validation = $this->characterCodeValidation($row['911']);
                        if ($validation) {
                            $student_details['911'] = $row['911'];
                            $row_validation['911'] = true;
                        } else {

                            $validation_description .= 'Uknown Grade "' . $row['911'] . '"';
                        }
                    }

                    //  return $row_validation

                    if (!in_array(false, $row_validation)) {


                        try {
                            DB::beginTransaction();

                            $student_details['class_id'] = $request->class;
                            $student_details['stream_id'] = $request->stream;
                            $student_details['created_by'] = auth()->user()->id;
                            $student_details['uuid'] = generateUuid();


                            foreach ($codes as $key => $code) {
                                $assessment = CharacterAssessmentReport::create(
                                    [
                                        'generated_exam_report_id' => $generated->id,
                                        'student_id' => $row['admission_number'],
                                        'code' => $code,
                                        'grade' => $row[$code],
                                        'attendance' => $row['attendance'],
                                        'late' => $row['late']
                                    ]
                                );
                            }
                            // return "tuko hapa jaman";
                            if ($assessment) {

                                DB::commit();
                                ++$success;
                            }
                        } catch (QueryException $e) {
                            DB::rollBack();
                            return $e->getMessage();
                        }
                    } else {
                        // return 'error';
                        $new_validation_errors = '';
                        $exploded_description = explode('.', $validation_description);

                        foreach ($exploded_description as $key => $validation) {
                            if ((count($exploded_description) - 2) == $key || count($exploded_description) - 1  == $key) {
                                $new_validation_errors .= $validation;
                            } else {
                                $new_validation_errors .= $validation . ',';
                            }
                        }
                        // $excel_dob_Date = getType($row['date_of_birth']) == 'string' ?  Date::stringToExcel($row['date_of_birth']) : $row['date_of_birth'];
                        // $excelDate = getType($row['date']) == 'string' ?  Date::stringToExcel($row['date']) : $row['date'];

                        $ca_upload_fail_validation[] = [
                            'admission_number' => $row['admission_number'],
                            'full_name' => $row['full_name'],
                            '901' => $row['901'],
                            '902' => $row['902'],
                            '903' => $row['903'],
                            '904' => $row['904'],
                            '905' => $row['905'],
                            '906' => $row['906'],
                            '907' => $row['907'],
                            '908' => $row['908'],
                            '909' => $row['909'],
                            '910' => $row['910'],
                            '911' => $row['911'],
                            'attendance' => $row['attendance'],
                            'late' => $row['late'],
                            'validation_description' =>   $new_validation_errors
                        ];

                        $index++;
                        DB::rollback();
                    }
                }

                if (count($ca_upload_fail_validation)) {
                    $failed = count($ca_upload_fail_validation);
                    $data = [
                        'failed' => $failed,
                        'success' => $success,
                        'msg' => 'Completed Processing',
                        'title' => 'success'
                    ];

                    ValidationError::updateOrCreate(
                        ['user_id' => auth()->user()->id],
                        ['payload' => json_encode($ca_upload_fail_validation)]
                    );
                } elseif (!count($ca_upload_fail_validation) && !$success) {

                    $data = [
                        'msg' => 'No Changes Made',
                        'title' => 'info'
                    ];
                } elseif (!count($ca_upload_fail_validation) && $success) {
                    $data = [
                        'failed' => $failed,
                        'success' => $success,
                        'msg' => 'Completed Processing',
                        'title' => 'success'
                    ];
                }

                return response()->json($data);
            }
        } catch (QueryException $e) {

            return response(['state' => 'error', 'msg' => $e->getMessage()]);
        }
    }


    protected function studentNameValidation($name)
    {

        /*
             * Name:
             * 1. -At least single Name
             * 2. -No number included
             * 3. -No special Characters
             * 4. String count > 1
             */

        $validation = array();
        $validation['status'] = true;
        if (preg_match("/[0-9'£!~`@#$%^&*(){}?<>_+.:,-;=]+/i", $name)) {
            $validation['status'] = false;
            $validation['msg'] = 'Name should Contain only Alpha Characters.';
        }
        return $validation;
    }


    public function characterCodeValidation($code)
    {

        $valid = false;
        $code = strtoupper($code);
        $characters = ['A', 'B', 'C', 'D', 'F'];

        foreach ($characters as $key => $character) {

            if ($code == $character) {
                $valid = true;
                break;
            }
        }

        return $valid;
    }





    public function downloadExcelValidationErrors()
    {

        $validation_errors = (json_decode(ValidationError::where('user_id', auth()->user()->id)->first()->payload));

        return \Maatwebsite\Excel\Facades\Excel::download(new CharacterAssessmentValidationError($validation_errors), 'Excel Failed Uploads.xlsx');
    }
}
