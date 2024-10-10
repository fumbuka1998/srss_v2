<?php

namespace App\Http\Controllers\results;

use App\Exports\ResultFailValidationExport;
use App\Http\Controllers\Controller;
use App\Imports\MarksImport;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\FileUpload;
use App\Models\Result;
use App\Models\GradeGroup;
use App\Models\SchoolClass;
use App\Models\ExamSchedule;
use App\Models\ExamScheduleClassStreamSubject;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ValidationError;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ResultsUploadExcelController extends Controller
{
    public $global;


    public function __construct()
    {
        $this->global = new GlobalHelpers();
    }



    public function finalize(Request $req)
    {

        $class_id = $req->class_id;
        $semester_id = $req->semester_id;
        $academic_year = $req->year_id;
        $exam_type = $req->exam_type;
        $subject_id = $req->subject_id;
        $stream_id = $req->stream_id;

        $results = Student::join('results', 'students.id', 'results.student_id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select('results.score', 'results.uuid', 'results.student_id', 'results.academic_year_id', 'results.semester_id', 'results.stream_id', 'results.subject_id', 'results.status', 'students.firstname', 'students.middlename', 'students.lastname')
            ->where('results.class_id', $class_id)
            ->where('results.semester_id', $semester_id)
            ->where('results.academic_year_id', $academic_year)
            ->where('results.status', 'PENDING');

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
        // return $results->get();
        try {

            DB::beginTransaction();
            foreach ($results->get() as $key => $result) {
                $update = Result::where('uuid', $result->uuid)->first()->update(['status' => 'COMPLETED']);
            }


            DB::commit();

            if ($update) {
                return response(['state' => 'done', 'title' => 'success', 'msg' => 'Marks Completed']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function updateScore(Request $req)
    {


        $uuid = $req->uuid;

        try {

            $update =  Result::where('uuid', $uuid)->first()->update(['score' => $req->score]);

            if ($update) {
                $data = ['state' => 'done', 'title' => 'success', 'msg' => 'update success'];
                return response($data);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function edit(Request $req)
    {

        $uuid = $req->uuid;
        try {

            $result = Result::where('uuid', $uuid)->first();
            return response($result);
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }

    public function revert(Request $req)
    {
        // return "hapa";

        $class_id = $req->class_id;
        $semester_id = $req->semester_id;
        $academic_year = $req->year_id;
        $exam_type = $req->exam_type;
        $subject_id = $req->subject_id;
        $stream_id = $req->stream_id;

        $results = Student::join('results', 'students.id', 'results.student_id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select('results.score', 'results.uuid', 'results.student_id', 'results.academic_year_id', 'results.semester_id', 'results.stream_id', 'results.subject_id', 'results.status', 'students.firstname', 'students.middlename', 'students.lastname')
            ->where('results.class_id', $class_id)
            ->where('results.semester_id', $semester_id)
            ->where('results.academic_year_id', $academic_year)
            ->where('results.status', 'COMPLETED');

        // return($results->get());

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

        try {

            DB::beginTransaction();
            foreach ($results->get() as $key => $result) {
                $foundResult = Result::where('uuid', $result->uuid)->first();
                if ($foundResult) {
                    $update = $foundResult->update(['status' => 'PENDING']);
                } else {
                    Log::error("Result not found for UUID: " . $result->uuid);
                }
                // $update = Result::where('uuid', $result->uuid)->first()->update(['status' => 'PENDING']);
            }


            DB::commit();

            if ($update) {
                return response(['state' => 'done', 'title' => 'success', 'msg' => 'Returned For Marking']);
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }




    public function preImportMarks(Request $request)
    {
        //  return $request;
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $success = 0;
        $failed = 0;

        $result_fail_validation = [];
        $import = new MarksImport();
        $filePath = $request->file->path();
        $extension = $request->file->getClientOriginalExtension();
        $xlsx = ExcelExcel::XLSX;
        $xls = ExcelExcel::XLS;
        $csv = ExcelExcel::CSV;
        $formats = [$xlsx, $xls, $csv];

        foreach ($formats as $key => $format) {
            if (strtolower($format) == strtolower($extension)) {
                $rows = Excel::toArray($import, $filePath, null, $format)[0];
            }
        }

        $html = '<table class="table table-bordered" style="width:100%;">
     <tr> <th> Admission No#  </th> <th> Full Name  </th> <th> Marks  </th> <th> </th> </tr>';

        if (count($rows)) {

            foreach ($rows as $index => $row) {
                $student = Student::where('id', $row['admission_number'])->first();

                if ($student) {
                    $studentId = $student->id;

                    $html .= '<tr>
                    <td>' . $row['admission_number'] . '</td>
                    <td class="full-name">' . $row['full_name'] . '</td>
                    <td> <input class="marks" value="' . $row['score'] . '"
                        data-student-id="' . $studentId . '"
                        data-academic-year="' . $request->academic_year . '"
                        data-grading-profile="' . $request->grading_profile . '"
                        data-semester="' . $request->term . '"
                        data-class-id="' . $request->class_sbmt . '"
                        data-stream-id="' . $request->stream_id . '"
                        data-subject-id="' . $request->subjects_sbmt . '"
                        data-exam-type="' . $request->exams_sbmt . '"
                        data-uuid="' . $request->this_uuid . '"
                        data-sp="' . $request->sp . '"
                        > ' . $row['score'] . ' </td>
                    <td class="validation-cell">';

                    if ($row['score'] !== null && $row['score'] !== '' && (is_numeric($row['score']) || $row['score'] === 'x' || $row['score'] === 's')) {
                        $html .= '<i style="color:#069613" class="fa-solid fa-circle-check"></i>';
                    } else {
                        $html .= '<i style="color:red" class="fa fa-times-circle"></i>';
                    }

                    $html .= '</td>
                </tr>';
                }
            }
        }

        $html .= '</table>';

        return response($html);
    }


    public function importResults(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $success = 0;
        $failed = 0;

        $result_fail_validation = [];
        $import = new MarksImport();
        $filePath = $request->file->path();
        $extension = $request->file->getClientOriginalExtension();
        $xlsx = ExcelExcel::XLSX;
        $xls = ExcelExcel::XLS;
        $csv = ExcelExcel::CSV;
        $formats = [$xlsx, $xls, $csv];

        foreach ($formats as $key => $format) {
            if (strtolower($format) == strtolower($extension)) {
                $rows = Excel::toArray($import, $filePath, null, $format)[0];
            }
        }



        try {

            ini_set('max_execution_time', 0);

            //initiate the most important thing to take place here i

            if (count($rows)) {

                foreach ($rows as $index => $row) {

                    $validation_description = '';
                    $row_validation = [
                        'score' => false,
                        'admission_number' => false,
                        'full_name' => false,
                    ];

                    if (isset($row['score'])) {

                        $mark =   $this->global->isValidScore($row['score']);

                        if ($mark) {

                            $row_validation['score'] = true;
                            $mark_details['score'] = $mark;
                        } else {

                            $validation_description .= 'Invalid Marks .';
                        }
                    } else {
                        $validation_description .= 'Marks Field Empty .';
                    }


                    if (isset($row['admission_number'])) {

                        $row_validation['admission_number'] = true;
                        $mark_details['student_id'] = $row['admission_number'];
                    } else {

                        $validation_description .= 'Admission Number Cant Be Empty';
                    }


                    if (isset($row['full_name'])) {

                        $row_validation['full_name'] = true;
                        $mark_details['full_name'] = $row['full_name'];
                    } else {

                        $validation_description .= 'Full Name Cant Be Empty';
                    }



                    if (!in_array(false, $row_validation)) {

                        try {

                            $mark_details['academic_year_id'] = $request->academic_year;
                            $mark_details['semester_id'] = $request->term;
                            $mark_details['class_id'] = $request->class_sbmt;
                            $mark_details['stream_id'] = $request->stream_sbmt;
                            $mark_details['subject_id'] = $request->subjects_sbmt;
                            $mark_details['exam_id'] = $request->exams_sbmt;
                            $mark_details['uuid'] = generateUuid();

                            $result = Result::updateOrCreate(
                                [
                                    'uuid' => $request->uuid
                                ],

                                $mark_details

                            );

                            if ($result) {

                                DB::commit();
                                ++$success;
                            }
                        } catch (QueryException $e) {

                            DB::rollBack();
                            return $e->getMessage();
                        }
                    } else {

                        $new_validation_errors = '';
                        $exploded_description = explode('.', $validation_description);
                        foreach ($exploded_description as $key => $validation) {
                            if ((count($exploded_description) - 2) == $key || count($exploded_description) - 1  == $key) {
                                $new_validation_errors .= $validation;
                            } else {

                                $new_validation_errors .= $validation . ',';
                            }
                        }

                        $result_fail_validation[] = [
                            'score' => $row['score'],
                            'admission_number' => $row['admission_number'],
                            'full_name' => $row['full_name'],
                            'validation_description' =>   $new_validation_errors
                        ];

                        $index++;

                        DB::rollback();
                    }
                }

                if (count($result_fail_validation)) {

                    $failed = count($result_fail_validation);
                    $encrypted_data = encrypt($result_fail_validation);
                    $data = [
                        'failed' => $failed,
                        'success' => $success
                    ];

                    ValidationError::updateOrCreate(
                        ['user_id' => 1],
                        ['payload' => json_encode($result_fail_validation)]
                    );
                    return response()->json($data);
                }
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    // store max by excel
    public function store_og(Request $req)
    {

        try {

            //  return $req;
            // $academicYear = $req->input('marks.5.academic_year');
            // return $academicYear;

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
            $data['grade_group'] = $req->gp;

            $grade_group_id = GradeGroup::where('uuid', $req->gp)->first()->id;


            foreach ($req->marks as $key => $mark) {

                if ($mark) {
                    $result = Result::updateOrCreate(
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

            if ($result) {

                $schedule_id = ExamSchedule::where('uuid', $req->uuid)->first()->id;
                ExamScheduleClassStreamSubject::where(['uuid' => $req->sp, 'exam_schedule_id' => $schedule_id])->first()->delete();
                return response(['state' => 'done', 'success_count' => $success, 'data' => $data, 'failed_count' => $failed, 'type' => 'success', 'msg' => 'success']);
            }

            return response(['state' => 'fail', 'type' => 'error', 'failed_count' => $failed, 'msg' => 'Failed']);
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function store(Request $req)
    {
        try {
            // dd($req);
            DB::beginTransaction();

            $success = 0;
            $failed = 0;
            $results = [];

            foreach ($req->marks as $key => $mark) {

                if ($mark && isset($mark['full_name'])) {
                    $data['academic_year_id'] = $mark['academic_year'];
                    $data['term_id'] = $mark['semester'];
                    $data['class_id'] = base64_decode($mark['class_id']);
                    $data['stream_id'] = base64_decode($mark['stream_id']);
                    $data['subject_id'] = base64_decode($mark['subject_id']);
                    $data['uuid_schedule'] = $mark['uuid'];
                    $data['exam_id'] = $mark['exam_type'];
                    $data['grade_group'] = $mark['gp'];
                    $data['sp_uuid'] = $mark['sp'];

                    $grade_group_id = GradeGroup::where('uuid', $mark['gp'])->first()->id;

                    $result = Result::updateOrCreate(
                        [
                            'uuid' => $mark['uuid']
                            // 'student_id' => $mark['student_id']
                        ],
                        [
                            'student_id' => $mark['student_id'],
                            'exam_id' => $mark['exam_type'],
                            'score' => $mark['marks'],
                            'full_name' => $mark['full_name'],
                            'uuid' => generateUuid(),
                            'semester_id' => $mark['semester'],
                            'academic_year_id' => $mark['academic_year'],
                            'subject_id' => base64_decode($mark['subject_id']),
                            'class_id' => base64_decode($mark['class_id']),
                            'stream_id' => base64_decode($mark['stream_id']),
                            'grade_group_id' => $grade_group_id,
                            'created_by' => auth()->user()->id
                        ]
                    );

                    $results[] = $result;
                    $success++;
                } else {
                    $failed++;
                }
            }

            DB::commit();

            if ($result) {
                $schedule_id = ExamSchedule::where('uuid', $mark['uuid'])->first()->id;
                ExamScheduleClassStreamSubject::where(['uuid' => $mark['sp'], 'exam_schedule_id' => $schedule_id])->first()->delete();
                return response(['state' => 'done', 'success_count' => $success, 'data' => $data, 'failed_count' => $failed, 'type' => 'success', 'msg' => 'success']);
            }

            return response(['state' => 'fail', 'type' => 'error', 'failed_count' => $failed, 'msg' => 'Failed']);
        } catch (QueryException $e) {
            DB::rollBack();
            return response(['state' => 'fail', 'type' => 'error', 'failed_count' => $failed, 'msg' => 'Failed', 'error' => $e->getMessage()]);
        }
    }





    public function downloadExcelValidationErrors()
    {
        // 'user_id',auth()->user()->id
        $validation_errors = json_decode(ValidationError::where('user_id', 1)->first()->payload);
        return Excel::download(new ResultFailValidationExport($validation_errors), 'Marks Failed Uploads.xlsx');
    }


    public function getIncompleteMarksCount()
    {

        return $this->global->getIncompletedMarksCount();
    }


    public function getCompleteMarksCount()
    {

        return $this->global->completedMarksCount();
    }


    public function getDraftMarksCount()
    {

        return $this->global->getDraftedMarksCount();
    }


    public function getIncompleteMarkingEditableDatatable(Request $request)
    {

        try {
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
                ->whereNull('results.deleted_at')
                ->where('results.status', 'PENDING');

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
                        // return $inc->grade_group_id;
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

                ->addColumn('action', function ($inc) use ($examInfo) {
                    $total_marks =  $examInfo->total_marks;
                    return '<span>
         <button type="button" data-max-score="' . $total_marks . '" data-min-score="0" data-uuid="' . $inc->uuid . '" class="btn btn-primary btn-sm edit"><i class="fa fa-edit"></i></button>
     </span>';
                })

                ->rawColumns(['action', 'education_level_id'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }



    public function getIncompleteMarkingEditable($year_id, $semester_id, $class_id, $stream_id, $exam_id, $subject_id)
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
        $data['upcomingForMarkingCount'] = $this->global->upcomingForMarkingCount();
        $data['draftsCount'] = $this->global->getDraftedMarksCount();
        $data['activeTab'] = 'incompleteTab';
        //   return $data;
        return view('results.marking.incompleted_marking_editable')->with($data);
    }
}
