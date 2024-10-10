<?php

namespace App\Http\Controllers\students_management;

use App\Exports\ResultFailValidationExport;
use App\Exports\StudentsUploadFailValidationExport;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\Exam;
use App\Models\Club;
use App\Models\Contact;
use App\Models\PredefinedComment;
use App\Models\User;
use App\Models\ClassTeacher;
use App\Models\Comment;
use App\Models\ContactPerson;
use App\Models\Country;
use App\Models\House;
use App\Models\Religion;
use App\Models\ReligionSect;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\ExamReport;
use App\models\Result;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentClub;
use App\Models\StudentParentRelationship;
use App\Models\StudentResultReport;
use App\Models\StudentSubjectsAssignment;
use App\Models\GeneratedExamReport;
use App\Models\UserHasRoles;
use App\Models\ValidationError;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;
use GlobalHelpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Maatwebsite\Excel\Excel as ExcelExcel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\returnSelf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class StudentsController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }

    public function index()
    {

        $data['headers'] = $headers = [
            ['label' => 'Avatar', 'name' => 'avatar', 'data' => 'avatar'],
            ['label' => 'Full Name', 'name' => 'name', 'data' => 'name'],
            ['label' => 'Gender', 'name' => 'gender', 'data' => 'gender'],
            ['label' => 'Date Of Birth', 'name' => 'dob', 'data' => 'dob'],
            ['label' => 'Class', 'name' => 'class_id', 'data' => 'class'],
            ['label' => 'Stream', 'name' => 'stream_id', 'data' => 'stream'],

            ['label' => 'Admission Number', 'name' => 'admission_no', 'data' => 'admission_no'],
            ['label' => 'Admission Type', 'name' => 'admission_type', 'data' => 'admission_type'],
            ['label' => 'Nationality', 'name' => 'nationality', 'data' => 'nationality'],
            ['label' => 'Tribe', 'name' => 'tribe', 'data' => 'tribe'],
            ['label' => 'Religion', 'name' => 'religion_id', 'data' => 'religion'],
            ['label' => 'Religion_sect', 'name' => 'religion_sect_id', 'data' => 'religion_sect'],

            ['label' => 'Action', 'name' => 'action', 'data' => 'action'],


        ];

        $data['houses'] = House::all();
        $data['classes'] = SchoolClass::all();
        $data['streams'] = Stream::all();
        $data['religions'] = Religion::all();
        $data['religion_sects'] = ReligionSect::all();
        $data['clubs'] = Club::all();


        $data['additional_headers'] = [

            ['label' => 'Avatar', 'default' => true, 'data' => 'avatar', 'name' => 'avatar'],
            ['label' => 'Full Name', 'default' => true, 'data' => 'name', 'name' => 'name'],
            ['label' => 'Gender', 'default' => true, 'name' => 'gender', 'data' => 'gender'],
            ['label' => 'Date Of Birth', 'default' => true, 'name' => 'dob', 'data' => 'dob'],
            ['label' => 'Class', 'default' => true, 'name' => 'class_id', 'data' => 'class_id'],
            ['label' => 'Stream', 'default' => true, 'name' => 'stream_id', 'data' => 'stream_id'],


            ['label' => 'Tribe', 'default' => true, 'data' => 'tribe', 'name' => 'tribe'],
            ['label' => 'Religion', 'default' => true, 'data' => 'religion_name', 'name' => 'religion_name'],
            ['label' => 'Religion Sect', 'default' => true, 'data' => 'religion_sect', 'name' => 'religion_sect'],
            ['label' => 'Nationality', 'default' => true, 'data' => 'nationality', 'name' => 'nationality'],
            ['label' => 'Admission Type', 'default' => true, 'data' => 'admission_type', 'name' => 'admission_type'],
            ['label' => 'Admission Number', 'default' => true, 'data' => 'admission_number', 'name' => 'admission_number']


        ];


        if (auth()->user()->checkRole('Class Teacher')) {

            // $assignedClasses = auth()->user()->assignedClasses();

            // Extract class_id values
            // $classIds = array_column($assignedClasses, 'class_id');

            // $data['students'] = Student::whereIn('class_id', $classIds)->get();
            // $data['students'] = Student::whereIn('class_id',$assignedClasses)->get();

            $assignedClasses = auth()->user()->assignedClasses();
            $classIds = array_column($assignedClasses, 'class_id');
            $streamIds = array_column($assignedClasses, 'stream_id');

            $data['students'] = Student::whereIn('class_id', $classIds)
                ->whereIn('stream_id', $streamIds)
                ->get();
        } elseif (auth()->user()->checkRole('Admin')) {
            $data['students'] = SchoolClass::all();
        }
        //    return UserHasRoles::where('user_id',19)->get();
        return view('student-management.index')->with($data);
    }


    public function updateDtHeaders(Request $req)
    {
        // return $req;

        $data['headers'] = $headers = $req->header_array;

        $data['additional_headers'] = $additional_hearders = [

            ['label' => 'Avatar', 'default' => true, 'data' => 'avatar', 'name' => 'avatar'],
            ['label' => 'Full Name', 'default' => true, 'data' => 'name', 'name' => 'name'],
            ['label' => 'Gender', 'default' => true, 'name' => 'gender', 'data' => 'gender'],
            ['label' => 'Date Of Birth', 'default' => true, 'name' => 'dob', 'data' => 'dob'],
            ['label' => 'Class', 'default' => true, 'name' => 'class_id', 'data' => 'class_id'],
            ['label' => 'Stream', 'default' => true, 'name' => 'stream_id', 'data' => 'stream_id'],


            ['label' => 'Tribe', 'default' => true, 'data' => 'tribe', 'name' => 'tribe'],
            ['label' => 'Religion', 'default' => true, 'data' => 'religion', 'name' => 'religion'],
            ['label' => 'Religion Sect', 'default' => true, 'data' => 'religion_sect', 'name' => 'religion_sect'],
            ['label' => 'Nationality', 'default' => true, 'data' => 'nationality', 'name' => 'nationality'],
            ['label' => 'Admission Type', 'default' => true, 'data' => 'admission_type', 'name' => 'admission_type'],
            ['label' => 'Admission Number', 'default' => true, 'data' => 'admission_number', 'name' => 'admission_number'],

        ];

        // return $req;
        if ($req->value == 0) {

            $columnName = $req->header;

            $removedColumn = null;
            $headers = array_filter($headers, function ($column) use ($columnName, &$removedColumn) {
                if ($column['name'] === $columnName) {
                    $removedColumn = $column;
                    return false;
                }
                return true;
            });
        }

        // return $headers;

        return response(['state' => 'done', 'headers' => $headers]);
    }


    public function store()
    {

        $uuid = Uuid::uuid4()->toString();
        // $user = new User();
        // $user->uuid = $uuid;
        // $user->name = $validatedData['name'];
        // $user->email = $validatedData['email'];
        // // Set other properties as needed
        // $user->save();

    }


    public function destroy(Request $req, $uuid)
    {

        $student = Student::where('uuid', $uuid)->first()->delete();
        $students = Student::all();

        if ($student) {
            return response(['students' => $students]);
        }
    }

    public function destroyStudent(Request $req, $uuid)
    {
        $student = Student::where('uuid', $uuid)->first()->delete();
        $students = Student::all();

        if ($student) {
            // return response(['students'=>$students]);
            $data = ['state' => 'Done', 'title' => 'Successful', 'msg' => 'Students deleted successful', 'students' => $students];

            return response($data);
        }
    }



    // public function registerSingle()
    // {

    //     $data['activeRadio'] = "single";
    //     $data['nationalities'] = Country::all();
    //     $data['relationships'] = StudentParentRelationship::all();
    //     $data['religions'] = Religion::all();
    //     $data['houses'] = House::all();
    //     $data['clubs'] = Club::all();
    //     $data['streams'] = Stream::all();

    //     if (auth()->user()->checkRole('Class Teacher')) {

    //         $assignedClasses = auth()->user()->assignedClasses();
    //         return $data['classes'] = SchoolClass::whereIn('id', $assignedClasses)->get();
    //     } elseif (auth()->user()->checkRole('Admin')) {
    //        return $data['classes'] = SchoolClass::all();
    //     }
    //     return view('student-management.new_single_reg')->with($data);
    // }

    public function registerSingle()
    {

        $data['activeRadio'] = "single";
        $data['nationalities'] = Country::all();
        $data['relationships'] = StudentParentRelationship::all();
        $data['religions'] = Religion::all();
        $data['houses'] = House::all();
        $data['clubs'] = Club::all();
        $data['streams'] = Stream::all();

        if (auth()->user()->checkRole('Class Teacher')) {
            $assignedClasses = auth()->user()->assignedClasses();
            $classIds = array_column($assignedClasses, 'class_id');
            $data['classes'] = SchoolClass::whereIn('id', $classIds)->get();

            // $assignedClasses = auth()->user()->assignedClasses();
            // dd($assignedClasses);
            // $assignedClasses = auth()->user()->assignedClasses()->pluck('class_id')->toArray();

            // $data['classes'] = SchoolClass::whereIn('id', $assignedClasses)->get();
        } elseif (auth()->user()->checkRole('Admin')) {
            $data['classes'] = SchoolClass::all();
        }
        return view('student-management.new_single_reg')->with($data);
    }

    public function registerMultiple()
    {
        // return 'hia';

        $data['activeRadio'] = "multiple";

        if (auth()->user()->checkRole('Class Teacher')) {

        $assignedClasses = auth()->user()->assignedClasses();
        $classIds = array_column($assignedClasses, 'class_id');
        $classIds = array_unique($classIds);
          $data['classes'] = SchoolClass::whereIn('id', $classIds)->get();

        //   return  $data['classes'] = SchoolClass::whereIn('id', [$assignedClasses])->get();
        } elseif (auth()->user()->checkRole('Admin')) {
            $data['classes'] = SchoolClass::all();
        }
        $data['streams'] = Stream::all();
        $data['activeTab'] = 'downloadTemplateTab';
        return view('student-management.multiple_registration_new')->with($data);
    }

    public function registerMultiplePreview()
    {

        $data['activeRadio'] = "multiple";
        if (auth()->user()->checkRole('Class Teacher')) {

            $assignedClasses = auth()->user()->assignedClasses();
            $classIds = array_column($assignedClasses, 'class_id');
        $classIds = array_unique($classIds);
          $data['classes'] = SchoolClass::whereIn('id', $classIds)->get();

            // $data['classes'] = SchoolClass::whereIn('id', $assignedClasses)->get();
        } elseif (auth()->user()->checkRole('Admin')) {
            $data['classes'] = SchoolClass::all();
        }
        $data['streams'] = Stream::all();
        $data['activeTab'] = 'UploadTab';
        return view('student-management.multiple_registration_new_upload')->with($data);
    }

    public function datatable(Request $request)
    {


        try {

            // return Stream::where('class_id',2)->get();

            $students_og = Student::leftJoin('student_clubs', 'students.id', '=', 'student_clubs.student_id')
                ->select('students.*', 'student_clubs.club_id', 'clubs.name as club_name')
                ->leftjoin('clubs', 'clubs.id', '=', 'student_clubs.club_id')
                ->whereNull('students.deleted_at')
                ->orderBy('id', 'desc');




            $studentsResult = Student::leftJoin('student_clubs', 'students.id', '=', 'student_clubs.student_id')
                ->select('students.*', 'student_clubs.club_id', 'clubs.name as club_name')
                ->leftjoin('clubs', 'clubs.id', '=', 'student_clubs.club_id')
                ->where('students.isgraduated',0)
                ->whereNull('students.deleted_at');

            if (auth()->user()->checkRole('Class Teacher')) {
                $assignedClasses = auth()->user()->assignedClasses();
                // $classIds = array_column($assignedClasses, 'class_id');
                // $studentsResult->whereIn('class_id', $classIds);
                $classIds = array_column($assignedClasses, 'class_id');
                $streamIds = array_column($assignedClasses, 'stream_id');

                $studentsResult->whereIn('class_id', $classIds);
                $studentsResult->whereIn('stream_id', $streamIds);
            } elseif (auth()->user()->checkRole('Admin')) {
                $studentsResult = Student::leftJoin('student_clubs', 'students.id', '=', 'student_clubs.student_id')
                    ->select('students.*', 'student_clubs.club_id', 'clubs.name as club_name')
                    ->leftjoin('clubs', 'clubs.id', '=', 'student_clubs.club_id')
                    ->where('students.isgraduated',0)
                    ->whereNull('students.deleted_at');
            }

            $students = $studentsResult->orderBy('students.id', 'desc');


            //    $students = Student::leftJoin('student_clubs','students.id','=','student_clubs.student_id')
            //                         ->select('students.*','student_clubs.club_id','clubs.name as club_name')
            //                         ->leftjoin('clubs', 'clubs.id', '=','student_clubs.club_id')
            //                         ->where('stream_id',41)
            //                         ->where('class_id',1)
            //                          ->orderBy('id','desc');


            // return $students;
            $search = $request->search;

            //  return $search;
            // return $request->all();

            if (!empty($search)) {
                $students = $students->where(function ($query) use ($search) {
                    $query->where('students.firstname', 'like', '%' . $search . '%')
                        ->orWhere('students.lastname', 'like', '%' . $search . '%')
                        ->orWhere('students.middlename', 'like', '%' . $search . '%')
                        ->orWhere('students.admission_no', 'like', '%' . $search . '%');
                });
            }

            $students = $students->get();

            if (is_numeric($request->class_id)) {
                $students = $students->where('class_id', $request->class_id);
            }

            if (is_numeric($request->stream_id)) {
                $students = $students->where('stream_id', $request->stream_id);
                // ->where('class_id',$request->class_id);
            }
            if (is_numeric($request->club)) {

                $students = $students->where('club_id', $request->club);
            }

            if (is_numeric($request->house_id)) {
                $students = $students->where('house_id', $request->house_id);
            }

            if (is_numeric($request->religion_id)) {
                $students = $students->where('religion_id', $request->religion_id);
            }

            if (is_numeric($request->religion_sect)) {
                $students = $students->where('religion_sect_id', $request->religion_sect);
            }


            if (!empty($request->reg_from) && !empty($request->reg_to)) {
                $students = $students->whereBetween('registration_date', [$request->reg_from, $request->reg_to]);
            }


            return DataTables::of($students)

                ->addColumn('name', function ($student) {

                    return $student->full_name;
                })


                ->addColumn('avatar', function ($student) {
                    if ($student->profile_pic) {
                        // return $student->profile_pic;
                        $url = asset('storage/' . $student->profile_pic);

                    //   return  $url = asset('storage/' . $student->profile_pic);

                        $image = '
                            <div class="user-avatar bg-primary">
                                <div class="avatar-image" style="background-image: url(' . $url . ');"></div>
                            </div>
                        ';
                    } else {
                        $image = ' <div class="user-avatar bg-primary"> <span>' . $student->name_abbrv . '</span> </div>';
                    }
                    return $image;
                })


                ->addColumn('class', function ($student) {

                    if ($student->class_id) {

                        return $student->getClass->name;
                    }
                })

                ->addColumn('stream', function ($student) {

                    if ($student->stream_id) {

                        return $student->stream->name;
                    }
                })

                ->editColumn('dob', function ($student) {
                    return date("d M, Y", strtotime($student->dob));
                })
                //  | <a type="button" style="color:white" href="' . route('students.edit', $student->uuid) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>

                //  additional columns
                ->addColumn('admission_number', function ($student) {
                    $admissionNumber = $student->admission_no;
                    $result = $admissionNumber == 0 ? 'N/A' : $admissionNumber;
                    return $result;
                })

                ->addColumn('nationality', function ($student) {
                    return $student->nationality;
                })

                ->addColumn('tribe', function ($student) {
                    return $student->tribe ? $student->tribe : 'N/A';
                })

                ->addColumn('admission_type', function ($student) {
                    return $student->admission_type;
                })
                ->addColumn('religion', function ($student) {
                    return $student->religion ? $student->religion->name : 'N/A';
                })

                ->addColumn('religion_sect', function ($student) {
                    return $student->religionSect ? $student->religionSect->name : 'N/A';
                })


                ->addColumn('action', function ($student) {
                    return '<span style="display:center; justify-content:center; align-items:center">
        <a style="color:white" href="' . route('students.profile', $student->uuid) . '" type="button" class="btn btn-info text-center btn-sm"><i class="fa fa-eye"></i></a>
        | <button  style="color:white" data-uuid="' . $student->uuid . '" type="button"  class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
        </span>';
                })

                ->rawColumns(['action', 'avatar'])
                ->make();
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    /* EXPORTS AND IMPORTS */

    // public function importStudents(Request $req){

    //     try {

    //     $class_id = $req->class_filter;
    //     $req->stream_filter ?  $stream_id = $req->stream_filter : $stream_id = null;

    //    $excel = ( Excel::import(new StudentsImport($class_id,$stream_id) , $req->file('students_excel')));


    //    if($excel){

    //     $data = ['state'=>'Done', 'title'=>'Successful', 'msg'=>'Students Imported successful'];

    //     return response($data);

    //  }

    //  $data = ['state'=>'Fail', 'title'=>'Fail', 'msg'=>'Students could not be imported'];
    //    return  response($data);

    // } catch (QueryException $e) {

    //  $data = ['state'=>'Error', 'title'=>'Database error', 'msg'=>'Something went wrong!<br />' . $e->errorInfo[2]];
    //  return  response($data);
    // }

    // }




    public function preImportStudents(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $success = 0;
        $failed = 0;

        $students_upload_fail_validation = [];
        $import = new StudentsImport;
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


                    $excel_dob_Date = is_string($row['date_of_birth'])
                        ? Date::stringToExcel($row['date_of_birth'])
                        : $row['date_of_birth'];

                    $excel_admission_date = is_string($row['admission_date'])
                        ? Date::stringToExcel($row['admission_date'])
                        : $row['admission_date'];

                    $php_dob_Date = Date::excelToDateTimeObject($excel_dob_Date)->format('Y-m-d');
                    $php_admission_date = Date::excelToDateTimeObject($excel_admission_date)->format('Y-m-d');

                    $validation_description = '';
                    $row_validation = [
                        'first_name' => false,
                        'admission_number' => false,
                        'last_name' => false,
                        'gender' => false,
                        'date_of_birth' => false,
                        'nationality' => false,
                        'admission_date' => false,
                        'admission_type' => false,
                        'religion' => false,
                        'house' => false,
                        'club' => false,
                        'contact_person_relationship' => false,
                        'contact_person_full_name' => false,
                        'contact_person_occupation' => false,
                        'contact_person_phone' => false,
                    ];

                    /* commented for now */

                    if (isset($row['admission_number'])) {

                        $checkDuplicate = Student::where('admission_no', $row['admission_number'])->first();

                        if ($checkDuplicate) {

                            $validation_description .= 'Student Admission Number already exists in Database.';
                        } else {
                            $row_validation['admission_number'] = true;
                            $student_details['admission_no'] = $row['admission_number'];
                        }
                    } else {

                        $validation_description .= 'Admission Number Cant Be Empty.';
                    }

                    /* FIRST NAME VALIDATION */

                    if (isset($row['first_name'])) {

                        $validation = $this->studentNameValidation($row['first_name']);
                        if ($validation['status']) {
                            $row_validation['first_name'] = true;
                            $student_details['firstname'] = $row['first_name'];
                        } else {

                            $validation_description .= $validation['msg'];
                        }
                    } else {

                        $validation_description .= 'First Name Cant Be Empty.';
                    }


                    /* MIDDLE NAME VALIDATION */


                    if (isset($row['middle_name'])) {

                        $validation = $this->studentNameValidation($row['middle_name']);
                        if ($validation['status']) {
                            $student_details['middlename'] = $row['middle_name'];
                        } else {

                            $validation_description .= $validation['msg'];
                        }
                    }


                    /* LASTNAME VALIDATION */

                    if (isset($row['last_name'])) {

                        $validation = $this->studentNameValidation($row['last_name']);
                        if ($validation['status']) {
                            $row_validation['last_name'] = true;
                            $student_details['lastname'] = $row['last_name'];
                        } else {

                            $validation_description .= $validation['msg'];
                        }
                    } else {

                        $validation_description .= 'Last Name Can\'t Be Empty.';
                    }


                    if (isset($row['religion'])) {

                        $religion = $this->checkReligionValidity($row['religion']);

                        if ($religion['status']) {
                            $row_validation['religion'] = true;
                            $student_details['religion_id'] = $religion['id'];

                            if (isset($row['religion_sect'])) {

                                $religion_sect = $this->checkReligionSectValidity($row['religion_sect']);

                                if ($religion_sect['status']) {
                                    $student_details['religion_sect_id'] =  $religion_sect['id'];
                                } else {

                                    $validation_description .= $religion_sect['msg'];
                                }
                            }
                        } else {

                            $validation_description .= $religion['msg'];
                        }
                    } else {

                        $validation_description .= 'Religion Field Can\'t Be Empty.';
                    }


                    /* TRIBE */



                    if (isset($row['tribe'])) {

                        $student_details['tribe'] = $row['tribe'];
                    }



                    /* GENDER VALIDATION */

                    if (isset($row['gender'])) {

                        $gender =  $this->checkGenderValidity($row['gender']);


                        if ($gender['status']) {

                            $row_validation['gender'] = true;
                            $student_details['gender'] = $row['gender'];
                        } else {

                            $validation_description .= $gender['msg'];
                        }
                    } else {

                        $validation_description .= 'Gender Field Cant Be Empty.';
                    }


                    /* DOB VALIDATION */

                    if ($row['date_of_birth']) {

                        $dob = $this->studentProfiledDateValidationToDb($php_dob_Date);

                        if ($this->studentAgeValidation($dob)) {
                            $student_details['dob'] = $dob;
                            $row_validation['date_of_birth'] = true;
                        } else {

                            $validation_description .= ' ' . 'Age Validation Fail.';
                        }
                    } else {

                        $validation_description .= ' ' . 'Age Field cannot be Empty.';
                    }


                    /* ADMISSION DATE  */

                    if ($row['admission_date']) {

                        $admission_date = $this->studentProfiledDateValidationToDb($php_admission_date);

                        if ($admission_date) {

                            $student_details['admission_date'] = $admission_date;
                            $row_validation['admission_date'] = true;
                        } else {

                            $validation_description .= ' ' . 'Admission Date Validation Fail.';
                        }
                    } else {

                        $validation_description .= ' ' . 'Admission Date Field cannot be Empty.';
                    }



                    /* HOUSE  */

                    if (isset($row['house'])) {

                        $house = $this->checkHouseValidity($row['house']);

                        if ($house['status']) {

                            $row_validation['house'] = true;
                            $student_details['house_id'] = $house['id'];
                        } else {

                            $validation_description .= $house['msg'];
                        }
                    } else {

                        $validation_description .= 'House Field Cant Be Empty.';
                    }

                    if (isset($row['club'])) {
                        if ($this->checkClubValidity($row['club'])['status']) {

                            $row_validation['club'] = true;
                            $club_details['club_id'] = $this->checkClubValidity($row['club'])['id'];
                        } else {

                            $validation_description .= $this->checkClubValidity($row['club'])['msg'];
                        }
                    } else {

                        $validation_description .= 'Club Can\'t Be Empty.';
                    }



                    if (isset($row['contact_person_relationship'])) {

                        $relationship = $this->checkRelationshipValidity($row['contact_person_relationship']);

                        if ($relationship['status']) {

                            $row_validation['contact_person_relationship'] = true;
                            $contact_person_details['contact_person_relationship'] = $row['contact_person_relationship'];
                        } else {

                            $validation_description .= $relationship['msg'];
                        }
                    } else {

                        $validation_description .= 'Contact Person Relationship Cant Be Empty.';
                    }


                    if (isset($row['contact_person_full_name'])) {

                        $row_validation['contact_person_full_name'] = true;
                        $contact_person_details['contact_person_full_name'] = $row['contact_person_full_name'];
                    } else {

                        $validation_description .= 'Contact Person Full Name Cant Be Empty.';
                    }


                    if (isset($row['contact_person_occupation'])) {

                        $row_validation['contact_person_occupation'] = true;
                        $contact_person_details['contact_person_occupation'] = $row['contact_person_occupation'];
                    } else {

                        $validation_description .= 'Contact Person Occupation Cant Be Empty.';
                    }


                    if (isset($row['nationality'])) {

                        $relationship = $this->checkNationalityValidity($row['nationality']);

                        if ($relationship['status']) {

                            $row_validation['nationality'] = true;
                            $student_details['nationality'] = $row['nationality'];
                        } else {

                            $validation_description .= $relationship['msg'];
                        }
                    } else {

                        $validation_description .= 'Nationality Field Cant Be Empty.';
                    }


                    if (isset($row['contact_person_phone'])) {

                        $validation = $this->contactPersonTelephoneValidation($row['contact_person_phone']);
                        if ($validation) {
                            $row_validation['contact_person_phone'] = true;
                            $contact_person_details['contact_person_phone'] = $row['contact_person_phone'];
                        } else {

                            $validation_description .= 'Phone Verification Failed.';
                        }
                    } else {

                        $validation_description .= 'Contact Person Phone Cant Be Empty.';
                    }


                    if (isset($row['admission_type'])) {

                        $relationship = $this->checkAdmissionTypeValidity($row['admission_type']);

                        if ($relationship['status']) {

                            $row_validation['admission_type'] = true;
                            $student_details['admission_type'] = $row['admission_type'];
                        } else {

                            $validation_description .= $relationship['msg'];
                        }
                    } else {

                        $validation_description .= 'Admission Type Field Cant Be Empty.';
                    }
                    //  return $row_validation;

                    if (!in_array(false, $row_validation)) {

                        try {

                            DB::beginTransaction();

                            $student_details['class_id'] = $request->class;
                            $student_details['stream_id'] = $request->stream;
                            $student_details['created_by'] = auth()->user()->id;
                            $student_details['uuid'] = generateUuid();

                            // $class_id = $req->class_filter;
                            // $req->stream_filter ?  $stream_id = $req->stream_filter : $stream_id = null;

                            // $excel = ( Excel::import(new StudentsImport($class_id,$stream_id) , $req->file('students_excel')));

                            $student = Student::create(
                                $student_details
                            );

                            /* STUDENT CLUBS */
                            StudentClub::create([

                                'student_id' => $student->id,
                                'club_id' => $club_details['club_id']

                            ]);
                            $contactPerson =  ContactPerson::create(
                                [
                                    'full_name' => $contact_person_details['contact_person_full_name'],
                                    'occupation' => $contact_person_details['contact_person_occupation'],
                                    'relationship' => $contact_person_details['contact_person_relationship'],
                                    'personable_type' => Student::class,
                                    'personable_id' => $student->id,

                                ]
                            );

                            Contact::create(
                                [
                                    'contact_type_id' => 1,
                                    'contact' => $contact_person_details['contact_person_phone'],
                                    'contactable_id' => $contactPerson->id,
                                    'contactable_type' => ContactPerson::class,

                                ]
                            );


                            if ($student) {

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

                        // $excel_dob_Date = getType($row['date_of_birth']) == 'string' ?  Date::stringToExcel($row['date_of_birth']) : $row['date_of_birth'];
                        // $excelDate = getType($row['date']) == 'string' ?  Date::stringToExcel($row['date']) : $row['date'];

                        $students_fail_validation[] = [

                            'admission_number' => $row['admission_number'],
                            'first_name' => $row['first_name'],
                            'middle_name' => $row['middle_name'],
                            'last_name' => $row['last_name'],
                            'gender' => $row['gender'],
                            'date_of_birth' => $php_dob_Date,
                            'nationality' => $row['nationality'],
                            'tribe' => $row['tribe'],
                            'address' => $row['address'],
                            'phone' => $row['phone'],
                            'email' => $row['email'],
                            'admission_date' =>  $admission_date,
                            'admission_type' => $row['admission_type'],
                            'religion' => $row['religion'],
                            'religion_sect' => $row['religion_sect'],
                            'house' => $row['house'],
                            'club' => $row['club'],
                            'is_disabled' => $row['is_disabled'],
                            'contact_person_relationship' => $row['contact_person_relationship'],
                            'contact_person_occupation' => $row['contact_person_occupation'],
                            'contact_person_phone' => $row['contact_person_phone'],
                            'contact_person_full_name' => $row['contact_person_full_name'],
                            'validation_description' =>   $new_validation_errors
                        ];

                        $index++;

                        DB::rollback();
                    }
                }

                if (count($students_fail_validation)) {

                    $failed = count($students_fail_validation);
                    $data = [
                        'failed' => $failed,
                        'success' => $success,
                        'msg' => 'Completed Processing',
                        'title' => 'success'
                    ];

                    ValidationError::updateOrCreate(
                        ['user_id' => auth()->user()->id],
                        ['payload' => json_encode($students_fail_validation)]
                    );
                    return response()->json($data);
                }
            }
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }


    public function downloadExcelValidationErrors()
    {

        $validation_errors = (json_decode(ValidationError::where('user_id', auth()->user()->id)->first()->payload));

        return Excel::download(new StudentsUploadFailValidationExport($validation_errors), 'Excel Failed Uploads.xlsx');
    }

    public function preImportStudentsxx(Request $request)
    {

        // return $request->all();

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $success = 0;
        $failed = 0;

        $result_fail_validation = [];
        $import = new StudentsImport();
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

        return $rows;

        $html = '<table class="table table-bordered" style="width:100%;">
     <tr> <th> Admission No#  </th> <th> Full Name  </th> <th> Marks  </th> <th> </th> </tr>';

        if (count($rows)) {

            foreach ($rows as $index => $row) {

                return $row;

                $html .= '<tr>  <td> ' . $row['admission_number'] . ' </td> <td> ' . $row['full_name'] . '  </td> <td> <input class="marks" value"' . $row['score'] . '"> ' . $row['score'] . ' </td> <td> <i style="color:#069613" class="fa-solid fa-circle-check"></i>  </td>  </tr>';
            }
        }

        $html .= '</table>';

        return response($html);
    }



    public function profile($uuid)
    {
        $data['classes'] = SchoolClass::all();
        $data['streams'] = Stream::all();

        $data['student'] = $student = Student::leftjoin('school_classes', 'students.class_id', '=', 'school_classes.id')
            ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')
            ->select('students.religion_id', 'students.id', 'students.registration_date', 'students.nationality', 'students.gender', 'students.admission_type', 'students.class_id', 'students.tribe', 'students.stream_id', 'students.religion_sect_id', 'students.firstname', 'students.middlename', 'students.dob', 'students.uuid as student_uuid', 'students.lastname', 'streams.name as stream_name', 'school_classes.name as class_name', 'students.profile_pic')
            ->where('students.uuid', $uuid)->first();

        $data['assignedSubjects'] = $assignedSubjects = StudentSubjectsAssignment::join('subjects', 'student_subjects_assignments.subject_id', '=', 'subjects.id')
            ->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
            ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
            ->select('school_classes.name as class_name', 'student_subjects_assignments.subject_id', 'subjects.name as subject_name', 'student_subjects_assignments.id as the_id', 'student_subjects_assignments.subject_id')
            ->where('student_id', $student->id)->get();

        $data['class'] = SchoolClass::find($student->class_id);
        $data['stream'] = Stream::find($student->stream_id);

        $data['religion'] = Religion::find($student->religion_id) ? Religion::find($student->religion_id)->name : '';
        $data['religion_sect'] = ReligionSect::find($student->religion_sect_id) ? ReligionSect::find($student->religion_sect_id)->name : "";

        $data['clubs'] = StudentClub::join('clubs', 'clubs.id', '=', 'student_clubs.club_id')->select('clubs.name', 'student_clubs.student_id')->where('student_id', $student->id)->get();
        $data['activeTab'] = 'personalInfoTab';
        $data['uuid'] = $uuid;

        $data['imageUrl'] = '';
        if ($student->profile_pic) {
            $data['imageUrl'] = asset('storage/' . $student->profile_pic);
        }

        $data['age'] =  $this->global->ageCalculator($student->dob);

        // return $data;
        return view('student-management.profile')->with($data);
    }

    public function editStudentProfile(Request $req)
    {
        // return $req;
        $student_info = Student::where('uuid', $req->uuid)->first();
        return response($student_info);
    }

    public function updateStudentBasic_og(Request $req)
    {
        //   return $req;
        // dd($req);
        try {
            //code...
            $registrationDate = Carbon::createFromFormat('Y-m-d', $req->registration_date)->toDateString();

            // $studentUpdate = Student::where('uuid',$req->uuid)->first();
            $studentUpdate = Student::updateOrCreate(
                [
                    'uuid' => $req->uuid
                ],
                [
                    'firstname' => $req->firstname,
                    'middlename' => $req->middlename,
                    'lastname' => $req->lastname,
                    'tribe' => $req->tribe,
                    'dob' => $req->dob,
                    'registration_date' => $registrationDate,
                    'admission_no' => $req->registration_no,
                    'class_id' => $req->students_class,
                    'stream_id' => $req->students_stream
                ]
            );

            if ($studentUpdate) {
                return response(['state' => 'done', 'msg' => 'success upate of student', 'title' => 'success']);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response(['state' => 'fail', 'msg' => 'error on upate of student', 'title' => 'fail']);
        }
    }

    // latest which is working
    public function updateStudentBasic(Request $req)
    {
        try {
            $registrationDate = Carbon::createFromFormat('Y-m-d', $req->registration_date)->toDateString();

            // Fetch the existing student record or create a new one if not found
            $student = Student::updateOrCreate(
                ['uuid' => $req->uuid],
                [
                    'firstname' => $req->firstname,
                    'middlename' => $req->middlename,
                    'lastname' => $req->lastname,
                    'tribe' => $req->tribe,
                    'dob' => $req->dob,
                    'registration_date' => $registrationDate,
                    'admission_no' => $req->registration_no,
                    'class_id' => $req->students_class,
                    'stream_id' => $req->students_stream
                ]
            );

            if ($student) {
                $currentYear = Carbon::now()->year;
                $academicYear = AcademicYear::where('name', $currentYear)->first();

                if ($academicYear) {
                    if ($student->wasChanged(['class_id', 'stream_id'])) {
                        Result::where('academic_year_id', $academicYear->id)
                            ->where('student_id', $student->id)
                            ->update([
                                'class_id' => $student->class_id,
                                'stream_id' => $student->stream_id
                            ]);
                    }
                }

                return response(['state' => 'done', 'msg' => 'Success update of student', 'title' => 'Success']);
            }
        } catch (\Throwable $th) {
            return response(['state' => 'fail', 'msg' => 'Error on update of student', 'title' => 'Fail']);
        }
    }




    public function editProfilePic_og($student, $file)
    {

        $directory = 'students/' . $student->id . '/' . 'student_profile_pics';
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $mime_type = $file->getClientMimeType();
        if (substr($mime_type, 0, 5) == "image") {
            $avatar_name = $file->getClientOriginalName();
            $path = $file->storeAs($directory, $avatar_name, 'public');
            // Log::info('Storage Path: ' . $path);
            $student->update(['profile_pic' => $path]);
        }
    }

    public function addProfilePic($student, $file)
    {

        $directory = 'students/' . $student->id . '/' . 'student_profile_pics';
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $mime_type = $file->getClientMimeType();
        if (substr($mime_type, 0, 5) == "image") {
            $avatar_name = $file->getClientOriginalName();
            $path = $file->storeAs($directory, $avatar_name, 'public');
            // Log::info('Storage Path: ' . $path);
            $student->update(['profile_pic' => $path]);
        }
    }

    public function updateProfilePic(Request $req, $student_uuid)
    {
        // return $req;
        $student = Student::where('uuid', $student_uuid)->first();

        if ($req->file('file')) {
            $rules = [
                // 'file' => 'required|image|max:2048',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:max_width=132,max_height=185|max:30',
            ];

            $validator = \Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $mime_type = $req->file('file')->getClientMimeType();

            if (substr($mime_type, 0, 5) == "image") {
                if ($req->hasfile('file')) {
                    $avatar_name = $req->file('file')->getClientOriginalName();
                    $path = $req->file('file')->storeAs('students/' . $student->id . '/' . 'student_profile_pics', $avatar_name, 'public');
                    $student->update(['profile_pic' => $path]);
                }
            }
        }

        // return response()->json(['success' => true]);
        $data = ['state' => 'Done', 'title' => 'Successful', 'msg' => 'Image edited successful'];

        return response($data);
    }


    public function studentsExcelTemplateExport()
    {

        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet
        $sheet = $spreadsheet->getActiveSheet();

        // manually set table data value
        $sheet->setCellValue('A1', 'Admission Number');
        $sheet->setCellValue('B1', 'First Name');
        $sheet->setCellValue('C1', 'Middle Name');
        $sheet->setCellValue('D1', 'Last Name');
        $sheet->setCellValue('E1', 'Gender');
        $sheet->setCellValue('F1', 'Date Of Birth');
        $sheet->setCellValue('G1', 'Nationality');
        $sheet->setCellValue('H1', 'Tribe');
        $sheet->setCellValue('I1', 'Address');
        $sheet->setCellValue('J1', 'Phone');
        $sheet->setCellValue('K1', 'Email');
        $sheet->setCellValue('L1', 'Admission Date');
        $sheet->setCellValue('M1', 'Admission Type');
        $sheet->setCellValue('N1', 'Religion');
        $sheet->setCellValue('O1', 'Religion Sect');
        $sheet->setCellValue('P1', 'House');
        $sheet->setCellValue('Q1', 'Club');
        $sheet->setCellValue('R1', 'is Disabled');
        $sheet->setCellValue('S1', 'Contact Person Relationship');
        $sheet->setCellValue('T1', 'Contact Person Full Name');
        $sheet->setCellValue('U1', 'Contact Person Occupation');
        $sheet->setCellValue('V1', 'Contact Person Phone');


        $spreadsheet->getActiveSheet()->getStyle('A1:V1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('75EF24');
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
        $writer = new WriterXlsx($spreadsheet); // instantiate Xlsx

        $filename = 'students-template'; // set filename for excel file to be exported

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file

    }



    //edit student
    public function editStudent($uuid)
    {

        $data['student'] = $student = Student::leftjoin('religions', 'students.religion_id', '=', 'religions.id')
            ->leftjoin('religion_sects', 'religions.id', '=', 'religion_sects.religion_id')
            ->select('students.id as student_id', 'students.*', 'students.uuid as std_uuid')
            ->where('students.uuid', $uuid)
            ->first();
        $data['student_clubs'] = StudentClub::where('student_id', $student->id)->pluck('club_id');
        $data['nationalities'] = Country::all();
        $data['relationships'] = StudentParentRelationship::all();
        $data['religions'] = Religion::all();
        $data['houses'] = House::all();
        $data['clubs'] = Club::all();
        $data['classes'] = SchoolClass::all();
        // return$student;

        // $selectedClubs = $student->clubs->pluck('id')->toArray();

        $data['parent_contacts'] = ContactPerson::join('contacts', 'contact_people.id', '=', 'contacts.contactable_id')
            ->where('personable_type', Student::class)
            ->where('personable_id', $student->student_id)
            ->get(['contact_people.*', 'contacts.contact']);

        //  return $relationship =  $data['relationship'] = ContactPerson::where('personable_type',Student::class)->where('personable_id',$student->student_id)->get();

        // return $parent_phone = Contact::where('contactable_id',$relationship->contactable_id)->where('contactable_type',ContactPerson::class)->first()->contact;

        $data['phone'] = Contact::where(['contactable_id' => $student->id, 'contactable_type' => Student::class, 'contact_type_id' => 1])->first() ? Contact::where(['contactable_id' => $student->id, 'contactable_type' => Student::class, 'contact_type_id' => 1])->first()->contact : '';

        $data['email'] = Contact::where(['contactable_id' => $student->id, 'contactable_type' => Student::class, 'contact_type_id' => 2])->first() ? Contact::where(['contactable_id' => $student->id, 'contactable_type' => Student::class, 'contact_type_id' => 2])->first()->contact : '';

        $data['address'] = Contact::where(['contactable_id' => $student->id, 'contactable_type' => Student::class, 'contact_type_id' => 3])->first() ? Contact::where(['contactable_id' => $student->id, 'contactable_type' => Student::class, 'contact_type_id' => 3])->first()->contact : '';

        //    $data['relationship'] = Student::join('contact_people', 'students.id', '=', 'contact_people.personable_id')
        //         ->where('students.id', $student->id)
        //         ->first() ? Student::join('contact_people', 'students.id', '=', 'contact_people.personable_id')
        //         ->where('students.id', $student->id)
        //         ->first() : '';
        // dd($data['relationship']);
        $data['religion_sect'] = ReligionSect::all();

        // $data['religion_sect'] = ReligionSect::
        $data['imageUrl'] = '';
        if ($student->profile_pic) {
            $data['imageUrl'] = asset('storage/' . $student->profile_pic);
        }

        $data['age'] = $this->global->ageCalculator($student->dob);

        // dd($data);



        return view('student-management.edit_student')->with($data);
    }



    public function allWizardStepsInOneStore(Request $req)
    {

        try {

            DB::beginTransaction();

            $student =  Student::create(
                [
                    'uuid' => generateUuid(),
                    'admission_number' => $req->admission_number,
                    'registration_date' => $req->admitted_year,
                    'firstname' => $req->first_name,
                    'middlename' => $req->middle_name,
                    'lastname' => $req->last_name,
                    'address' => $req->student_address,
                    'gender' => $req->gender,
                    'dob' => $req->dob,
                    'tribe' => $req->tribe,
                    'created_by' => auth()->user()->id,
                    'religion_sect_id' => $req->religion_sect,
                    'house_id' => $req->house,
                    'nationality' => $req->nationality,
                    'isDisabled' => $req->is_disabled,
                    'religion_id' => $req->religion,
                    'admission_type' => $req->admission_type
                ]

            );


            $club = $req->club;

            if ($club) {

                StudentClub::create(
                    [
                        'student_id' => $student->id,
                        'club_id' => $club

                    ]

                );
            }

            //   image upload with no validation

            //   if($req->file('file')){

            //       $mime_type = $req->file('file')->getClientMimeType();
            //       if (substr($mime_type, 0, 5) == "image") {
            //           if($req->hasfile('file')){
            //           $avatar_name= $req->file('file')->getClientOriginalName();
            //           $path = $req->file('file')->storeAs('students/'.$student->id.'/'.'student_profile_pics', $avatar_name, 'public');
            //           $student->update(['profile_pic'=>$path]);
            //       }

            //       }
            //   }

            // image upload with validation
            if ($req->file('file')) {
                $rules = [
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:max_width=132,max_height=185|max:30',
                ];

                $validator = \Validator::make($req->all(), $rules);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->first()], 400);
                }

                $mime_type = $req->file('file')->getClientMimeType();

                if (substr($mime_type, 0, 5) == "image") {
                    if ($req->hasfile('file')) {
                        $avatar_name = $req->file('file')->getClientOriginalName();
                        $path = $req->file('file')->storeAs('students/' . $student->id . '/' . 'student_profile_pics', $avatar_name, 'public');
                        $student->update(['profile_pic' => $path]);
                    }
                }
            }


            if ($req->student_phone) {

                Contact::create(

                    [
                        'contact_type_id' => 1,
                        'contact' => $req->student_phone,
                        'contactable_id' => $student->id,
                        'contactable_type' => Student::class,

                    ]
                );
            }

            if ($req->student_email) {

                Contact::create(
                    [
                        'contact_type_id' => 2,
                        'contact' => $req->student_email,
                        'contactable_id' => $student->id,
                        'contactable_type' => Student::class,

                    ]
                );
            }

            if ($req->student_address) {


                Contact::create(
                    [
                        'contact_type_id' => 3,
                        'contact' => $req->student_address,
                        'contactable_id' => $student->id,
                        'contactable_type' => Student::class,

                    ]
                );
            }


            /* step 2 */

            if ($req->contact_person_relationship) {

                foreach ($req->contact_person_relationship as $key => $rltn) {

                    $contactPerson =  ContactPerson::create(
                        [
                            'full_name' => $req->contact_person_name[$key],
                            'occupation' => $req->contact_person_occupation[$key],
                            'relationship' => $rltn,
                            'personable_type' => Student::class,
                            'personable_id' => $student->id,

                        ]
                    );

                    Contact::create(
                        [
                            'contact_type_id' => 1,
                            'contact' => $req->contact_person_phone[$key],
                            'contactable_id' => $contactPerson->id,
                            'contactable_type' => ContactPerson::class,

                        ]
                    );
                }
            }


            /* step 3 */

            $student->update(['class_id' => $req->students_class, 'stream_id' => $req->students_stream]);


            /* step 4 */
            $file = $req->file;
            if ($file) {
                $this->addProfilePic($student, $file);
            }

            DB::commit();


            if ($student) {

                $data = ['state' => 'done', 'title' => 'success', 'msg' => 'Student Added Success'];
                return response($data);
            }

            $data = ['state' => 'fail', 'title' => 'fail', 'msg' => 'Action  Failed'];
            return response($data);
        } catch (QueryException $e) {

            return $e->getMessage();
        }
    }

    public function studentResultsIndex($uuid)
    {
        $data['activeTab'] = 'studentResultsTab';
        $data['student'] = $student = Student::leftjoin('school_classes', 'students.class_id', '=', 'school_classes.id')
            ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')
            ->select('students.religion_id', 'students.id', 'students.class_id', 'students.stream_id', 'students.religion_sect_id', 'students.firstname', 'students.middlename', 'students.dob', 'students.uuid as student_uuid', 'students.lastname', 'streams.name as stream_name', 'school_classes.name as class_name', 'students.profile_pic')
            ->where('students.uuid', $uuid)->first();

        $data['assignedSubjects'] = $assignedSubjects = StudentSubjectsAssignment::join('subjects', 'student_subjects_assignments.subject_id', '=', 'subjects.id')
            ->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
            ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
            ->select('school_classes.name as class_name', 'student_subjects_assignments.subject_id', 'subjects.name as subject_name', 'student_subjects_assignments.id as the_id', 'student_subjects_assignments.subject_id')
            ->where('student_id', $student->id)->get();

        $data['class'] = SchoolClass::find($student->class_id);
        $data['stream'] = Stream::find($student->stream_id);

        $data['uuid'] = $uuid;

        $data['imageUrl'] = '';
        if($student->profile_pic)
        {
            $data['imageUrl'] = asset('storage/'.$student->profile_pic);
        }

        $data['age'] =  $this->global->ageCalculator($student->dob);

        $id = Student::where('uuid', $uuid)->first()->id;

        $reports = StudentResultReport::where('student_id', $id)
            ->join('generated_exam_reports', 'student_result_reports.generated_exam_report_id', '=', 'generated_exam_reports.id')
            ->select('student_result_reports.*', 'generated_exam_reports.*')
            ->whereNull('deleted_at')
            ->get();





        foreach($reports as $report)
        {
            $data['reports'] = $report;
            $generated_exam_report = GeneratedExamReport::where('id',$report->generated_exam_report_id)->first();


            $data['report_infos'] = ExamReport::where('id', $report->exam_report_id)->get();


        }

        $collective = $col = StudentResultReport::where('student_id', $id)
            ->join('generated_exam_reports', 'student_result_reports.generated_exam_report_id', '=', 'generated_exam_reports.id')
            ->select('student_result_reports.*', 'generated_exam_reports.*')
            ->whereNull('deleted_at')
            ->where('generated_exam_reports.uuid', $generated_exam_report->uuid)
            ->get();

        $subjects = StudentSubjectsAssignment::join('subjects', 'subjects.id', '=', 'student_subjects_assignments.subject_id')->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
        ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
        ->select('streams.name as stream_name', 'student_subjects_assignments.student_id', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
        ->where(['student_subjects_assignments.class_id' => $student->class_id, 'student_subjects_assignments.stream_id' => $student->stream_id])
        ->groupBy('subjects.id');

        // return $data;

        $exam_type_combination = decrypt($generated_exam_report->exam_type_combination);
        $exam_report_id = $generated_exam_report->exam_report_id;

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




            foreach ($collective as $key => $col) {
// return $col->student_id;
                if ($col->student_id === $student) {
                    // return $student;
                    $assignd_subjects = $subjects->where('student_subjects_assignments.student_id', $student)->get();
                    $data['rst'][$student] = [];
                    $data['rst'][$student]['subjects'] = [];

                    $data['rst'][$student]['subjects'] = StudentSubjectsAssignment::join('subjects', 'subjects.id', '=', 'student_subjects_assignments.subject_id')->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
                        ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
                        ->select('streams.name as stream_name', 'student_subjects_assignments.student_id', 'school_classes.name as class_name', 'subjects.uuid', 'subjects.subject_type', 'subjects.id as sbjct_id', 'subjects.name as subject_name', 'streams.id as stream_id', 'school_classes.id as class_id', 'subjects.code as sbjct_code')
                        ->where(['student_subjects_assignments.class_id' => $student->class_id, 'student_subjects_assignments.stream_id' => $student->stream_id])
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
                }

                return view('student-management.graphy.results')->with($data);
            }


        return view('student-management.graphy.results')->with($data);
    }


    // results datatable student results
    public function studentResultsDatatable($uuid)
    {
        $profile_uuid = $uuid;
        $id = Student::where('uuid', $uuid)->first()->id;

        $reports = StudentResultReport::where('student_id', $id)
            ->join('generated_exam_reports', 'student_result_reports.generated_exam_report_id', '=', 'generated_exam_reports.id')
            ->select('student_result_reports.*', 'generated_exam_reports.*')
            ->whereNull('deleted_at')
            ->get();




        foreach ($reports as $report) {
            $report = $reports[0];
        }
        return Datatables::of($reports)
            ->addColumn('report_name', function ($reports) {
                $report = ExamReport::where('id', $reports->exam_report_id)->get();
                if ($report) {
                    return $report[0]['name'];
                }
            })
            // ->addColumn('exam_type_combination', function ($reports) {
            //     if ($reports->exam_type_combination) {
            //         // return base64_decode( $reports->exam_type_combination);
            //         return 'hapa';
            //     }
            // })
            ->addColumn('academic_year', function ($reports) {
                $year = AcademicYear::where('id', $reports->academic_year_id)->get();
                if ($year) {
                    return $year[0]['name'];
                }
            })
            ->addColumn('terms', function ($reports) {
                $sem =  Semester::where('id', $reports->term_id)->get();
                if ($sem) {
                    return $sem[0]['name'];
                }
            })
            ->addColumn('divisions', function ($reports) {
                if ($reports->division) {
                    return $reports->division;
                }
            })
            ->addColumn('points', function ($reports) {
                if ($reports->points) {
                    return $reports->points;
                }
            })

            ->addColumn('action', function ($report) use ($profile_uuid) {
                return '<span style="display:center; justify-content:center; align-items:center">

                <a target="_blank"
                    data-uuid="' . $report->uuid . '"
                    data-generated_exam_report_id="' . $report->generated_exam_report_id . '"
                    href="' . route('single.student.results.reports.generate.report.print', ['uuid' => $profile_uuid, 'report_uuid' => $report->uuid, 'generated_exam_report_uuid' => $report->generated_exam_report_id]) . '"
                    type="button"
                    class="btn btn-outline-info btn-sm print">
                    <i class="fa fa-print"></i>
                </a>

            </span>';
            })

            ->rawColumns(['action'])
            ->make();
    }

    // changing the ui of displaying the results
    // public function studentResultsDatatable($uuid)
    // {
    //     $profile_uuid = $uuid;
    //     $id = Student::where('uuid', $uuid)->first()->id;

    //     $data['reports'] =  $reports = StudentResultReport::where('student_id', $id)
    //         ->join('generated_exam_reports', 'student_result_reports.generated_exam_report_id', '=', 'generated_exam_reports.id')
    //         ->select('student_result_reports.*', 'generated_exam_reports.*')
    //         ->whereNull('deleted_at')
    //         ->get();

    //     return view('student-management.graphy.results')->with($data);


    // }

    // print method here   'student_id' => $this->global->base64url_encode($report->student_id)
    public function studentSingleReportPrint_myojijo($report_uuid)
    {
        // return $report_uuid;

        $id = Student::where('uuid', $report_uuid)->first()->id;

        return  $reports = StudentResultReport::where('student_id', $id)
            ->join('generated_exam_reports', 'student_result_reports.generated_exam_report_id', '=', 'generated_exam_reports.id')
            ->select('student_result_reports.*', 'generated_exam_reports.*')
            ->get();
    }

    public function studentSingleReportPrint($report_uuid, $generated_exam_report_uuid)
    {
        //  return $generated_exam_report_uuid;

        $id = Student::where('uuid', $report_uuid)->first()->id;

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

// return $collective;
            foreach ($collective as $key => $col) {
                // return $collective;

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

    // indrive single result
    public function studentSingleReportIndrive($uuid)
    {
        return $uuid;
    }


    public function contactPeopleIndex($uuid)
    {


        $data['activeTab'] = 'contactPersonTab';
        $data['student'] = $student = Student::leftjoin('school_classes', 'students.class_id', '=', 'school_classes.id')
            ->leftjoin('streams', 'students.stream_id', '=', 'streams.id')
            ->select('students.religion_id', 'students.id', 'students.class_id', 'students.stream_id', 'students.religion_sect_id', 'students.firstname', 'students.middlename', 'students.dob', 'students.uuid as student_uuid', 'students.lastname', 'streams.name as stream_name', 'school_classes.name as class_name', 'students.profile_pic')
            ->where('students.uuid', $uuid)->first();

        $data['assignedSubjects'] = $assignedSubjects = StudentSubjectsAssignment::join('subjects', 'student_subjects_assignments.subject_id', '=', 'subjects.id')
            ->join('school_classes', 'school_classes.id', '=', 'student_subjects_assignments.class_id')
            ->join('streams', 'streams.id', '=', 'student_subjects_assignments.stream_id')
            ->select('school_classes.name as class_name', 'student_subjects_assignments.subject_id', 'subjects.name as subject_name', 'student_subjects_assignments.id as the_id', 'student_subjects_assignments.subject_id')
            ->where('student_id', $student->id)->get();

        $data['class'] = SchoolClass::find($student->class_id);
        $data['stream'] = Stream::find($student->stream_id);

        $data['uuid'] = $uuid;

        $data['imageUrl'] = '';
        if ($student->profile_pic) {
            $data['imageUrl'] = asset('storage/' . $student->profile_pic);
        }

        $data['age'] =  $this->global->ageCalculator($student->dob);


        return view('student-management.graphy.parents_guardians')->with($data);
    }


    public function contactPeopleDatatable($uuid)
    {
        $id = Student::where('uuid', $uuid)->first()->id;
        $contact_persons = ContactPerson::select('contact_people.*')
            ->where('personable_type', Student::class)
            ->where('personable_id', $id)
            ->get();

        return DataTables::of($contact_persons)

            ->addColumn('address', function ($contact_person) {
                return $contact_person->Address;
            })
            ->editColumn('full_name', function ($contact_person) {

                return strtoupper($contact_person->full_name);
            })
            //  ->addColumn('email',function($contact_person){
            //      return $contact_person->Email;
            //  })
            ->addColumn('phone', function ($contact_person) {
                return $contact_person->Phone;
            })
            ->addColumn('action', function ($contact_person) use ($id) {
                $button = '';
                //    $button .= '  <a href="javacript:void(0)" class="button-icon button btn btn-sm rounded-small btn-warning  more-details-1"><i class="fa fa-eye m-0"></i> </a>';
                $button .= ' <a href="javascript:void(0)" data-contact_person_id="' . $contact_person->id . '" data-original-title="Edit" data-edit_btn="' . $id . '"  data-toggle="tooltip" class="button-icon button btn btn-sm rounded-small btn-info  editCntBtn" ><i class="fa fa-edit  m-0"></i></a>';
                $button .= ' <button  disabled data-contact_person_id="' . $contact_person->id . '" data-original-title="delete"  data-toggle="tooltip" class="button-icon button btn btn-sm rounded-small btn-danger  dltBtn" ><i class="fa fa-trash  m-0"></i></button>';

                return '<nobr>' . $button . '</nobr>';
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function contactPeopleStore(Request $request, $uuid)
    {


        try {

            DB::beginTransaction();

            $student_id = Student::where('uuid', $uuid)->first()->id;



            $contact_person = ContactPerson::updateOrCreate(
                [
                    'id' => $request->contact_person_id
                ],
                [
                    'full_name' => $request->contact_person_name,
                    'occupation' => $request->contact_person_occupation,
                    'relationship' => $request->contact_person_relationship,
                    'personable_type' => Student::class,
                    'personable_id' => $student_id,

                ]
            );

            Contact::updateOrCreate(
                [
                    'id' => $request->contact_person_contact_id
                ],
                [
                    'contact_type_id' => 1,
                    'contact' => $request->contact_person_phone,
                    'contactable_id' => $contact_person->id,
                    'contactable_type' => ContactPerson::class,

                ]
            );

            DB::commit();

            if ($contact_person) {
                $data = ['state' => 'done', 'msg' => 'record successfully created', 'title' => 'success'];
                return response($data);
            }

            $data = ['state' => 'fail', 'msg' => 'record not created', 'title' => 'info'];
            return response($data);
        } catch (QueryException $e) {
            DB::rollBack();
            $data = ['status' => 'ERROR', 'msg' => 'record successfully created', 'title' => 'error'];
            return response($data);
        }
    }

    public function contactPersonEdit(Request $req, $uuid, $id)
    {

        $data['contact_person'] = ContactPerson::with('contacts')->where('contact_people.id', $id)->first();
        return response($data);
    }


    /* SOME EXCEL VALIDATIONS */

    public function checkGenderValidity($gender)
    {

        $gender = strtolower($gender);
        $allowedGenders = ['male', 'female'];

        $userEnteredRelationship = $gender;

        $isExactMatch = in_array(strtolower($userEnteredRelationship), $allowedGenders);

        if ($isExactMatch) {
            $res['status'] = true;
            return $res;
        } else {
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach ($allowedGenders as $relationship) {
                $distance = levenshtein(strtolower($userEnteredRelationship), $relationship);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $relationship;
                }
            }
            $threshold = 2;
            if ($minDistance <= $threshold) {
                $res['status'] = false;
                $res['msg'] = "Suggested Gender: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "No close match found. Possible Gender: " . implode(", ", $allowedGenders) . '.';
            }
        }
        return $res;
    }


    protected function checkRelationshipValidity($relation)
    {

        $userEnteredRelationship = $relation;
        $res['status'] = true;

        $allowedRelationships = ["FRIEND", "FATHER", "MOTHER", "GUARDIAN", "HUSBAND", "WIFE"];
        $isExactMatch = in_array(strtoupper($userEnteredRelationship), $allowedRelationships);

        if ($isExactMatch) {
            return $res;
        } else {
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach ($allowedRelationships as $relationship) {
                $distance = levenshtein(strtoupper($userEnteredRelationship), $relationship);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $relationship;
                }
            }
            $threshold = 2;
            if ($minDistance <= $threshold) {
                $res['status'] = false;
                $res['msg'] = "Suggested Relationship: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "No close match found. Possible relationships: " . implode(", ", $allowedRelationships) . '.';
            }

            return $res;
        }
    }


    protected function checkClubValidity($club)
    {

        $allowedClubTypes = Club::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtoupper($name) => $id];
        })->toArray();

        $userEnteredRelationship = strtoupper($club);

        if (isset($allowedClubTypes[$userEnteredRelationship])) {
            $exactMatchId = $allowedClubTypes[$userEnteredRelationship];
            $res['status'] = true;
            $res['id'] =  $exactMatchId;
            $res['name'] =  $userEnteredRelationship;
            return $res;
        } else {
            // Calculate distances and find the closest match
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach ($allowedClubTypes as $id => $club) {
                $distance = levenshtein($userEnteredRelationship, $club);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $club;
                }
            }
            $threshold = 3;

            if ($minDistance <= $threshold) {
                $res['status'] =  false;
                $res['msg'] = "Suggested Club: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "Possible Clubs: " . implode(", ", array_keys($allowedClubTypes)) . '.';
            }
        }

        return $res;
    }


    protected function checkNationalityValidity($nation)
    {

        $allowedCountries = Country::pluck('name')->map(function ($name) {
            return strtoupper($name);
        })->toArray();


        $userEnteredRelationship = $nation;
        $isExactMatch = in_array(strtoupper($userEnteredRelationship), $allowedCountries);

        if ($isExactMatch) {
            $res['status'] = true;
            return $res;
        } else {
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach ($allowedCountries as $relationship) {
                $distance = levenshtein(strtoupper($userEnteredRelationship), $relationship);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $relationship;
                }
            }
            $threshold = 3;
            if ($minDistance <= $threshold) {
                $res['status'] = false;
                $res['msg'] = "Suggested Country: $closestMatch.";
            } else {

                $res['status'] = false;
                $res['msg'] = "Country not in Database.";
            }
        }

        return $res;
    }

    protected function checkAdmissionTypeValidity($admission_type)
    {


        $allowedAdmissionTypes = ['continuing', 'started', 'transfered'];

        $userEnteredRelationship = $admission_type;
        $isExactMatch = in_array(strtolower($userEnteredRelationship), $allowedAdmissionTypes);
        $res = array();


        if ($isExactMatch) {
            $res['status'] = true;
            return $res;
        } else {
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach ($allowedAdmissionTypes as $relationship) {
                $distance = levenshtein(strtolower($userEnteredRelationship), $relationship);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $relationship;
                }
            }
            $threshold = 5;
            if ($minDistance <= $threshold) {
                $res['status'] = false;
                $res['msg'] = "Suggested Admission Type: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "No close match found. Possible Admission Types: " . implode(", ", $allowedAdmissionTypes) . '.';
            }

            return $res;
        }
    }



    protected function checkReligionValidity($religion)
    {

        $allowedReligionTypes = Religion::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtoupper($name) => $id];
        })->toArray();

        $userEnteredRelationship = strtoupper($religion);

        if (isset($allowedReligionTypes[$userEnteredRelationship])) {
            $exactMatchId = $allowedReligionTypes[$userEnteredRelationship];
            $res['status'] = true;
            $res['id'] =  $exactMatchId;
            $res['name'] =  $userEnteredRelationship;
            return $res;
        } else {
            // Calculate distances and find the closest match
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach (array_keys($allowedReligionTypes) as $id => $religion) {
                $distance = levenshtein($userEnteredRelationship, $religion);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $religion;
                }
            }
            $threshold = 3;

            if ($minDistance <= $threshold) {
                $res['status'] =  false;
                $res['msg'] = " Suggested Religion: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "No close match found. Possible religions: " . implode(", ", array_keys($allowedReligionTypes)) . '.';
            }
        }

        return $res;
    }

    protected function checkHouseValidity($house)
    {

        $allowedHouseTypes = House::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtoupper($name) => $id];
        })->toArray();

        $userEnteredRelationship = strtoupper($house);

        if (isset($allowedHouseTypes[$userEnteredRelationship])) {
            $exactMatchId = $allowedHouseTypes[$userEnteredRelationship];
            $res['status'] = true;
            $res['id'] =  $exactMatchId;
            $res['name'] =  $userEnteredRelationship;
            return $res;
        } else {
            // Calculate distances and find the closest match
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach (array_keys($allowedHouseTypes) as $id => $house) {

                $distance = levenshtein($userEnteredRelationship, $house);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $house;
                }
            }
            $threshold = 3;

            if ($minDistance <= $threshold) {
                $res['status'] =  false;
                $res['msg'] = "Suggested House: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "No close match found. Possible houses: " . implode(", ", array_keys($allowedHouseTypes)) . '.';
            }
        }

        return $res;
    }


    /* RELIGION SECT VALIDATION */

    protected function checkReligionSectValidity($religion_sect)
    {

        $allowedReligionTypes = ReligionSect::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtoupper($name) => $id];
        })->toArray();

        $userEnteredRelationship = strtoupper($religion_sect);

        if (isset($allowedReligionTypes[$userEnteredRelationship])) {
            $exactMatchId = $allowedReligionTypes[$userEnteredRelationship];
            $res['status'] = true;
            $res['id'] =  $exactMatchId;
            $res['name'] =  $userEnteredRelationship;
            return $res;
        } else {
            // Calculate distances and find the closest match
            $closestMatch = "";
            $minDistance = PHP_INT_MAX;

            foreach (array_keys($allowedReligionTypes) as $id => $religion) {
                $distance = levenshtein($userEnteredRelationship, $religion);

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestMatch = $religion;
                }
            }
            $threshold = 3;

            if ($minDistance <= $threshold) {
                $res['status'] =  false;
                $res['msg'] = "Suggested Religion: $closestMatch.";
            } else {
                $res['status'] = false;
                $res['msg'] = "No close match found. Possible religion Sects: " . implode(", ", array_keys($allowedReligionTypes)) . '.';
            }
        }

        return $res;
    }




    public function checkForAdmissionNumberDuplicacy(Request $req)
    {
        $data['exists'] = 0;
        $duplicate = Student::where('admission_no', $req->admission_number)->get()->count();
        if ($duplicate) {
            $data['exists'] = 1;
        }
        return response($data);
    }





    // Use $res as neede


    protected function contactPersonTelephoneValidation($telephone)
    {

        if (isset($telephone)) {
            if (((strlen($telephone) > 8) or (strlen($telephone) < 13)) && (strlen($telephone) != 11)) {
                switch (strlen($telephone)) {
                    case 13:
                        return (substr($telephone, 0, 4) == '+255') ? $telephone : false;

                    case 9:
                        return $telephone;
                    case 10:
                        return (substr($telephone, 0, 1) == 0) ? $telephone : false;

                    case 12:
                        return (substr($telephone, 0, 3) == '255') ? $telephone : false;

                    default:
                        return false;
                }
            }
        }
        return true;
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

    protected function studentAgeValidation($dob)
    {
        // return $dob;

        $date = new DateTime($dob);
        $now = new DateTime();
        $interval = $now->diff($date);
        $year = $interval->y;

        return ($year >= 0 && $year < 120) ? true : false;
    }


    protected function studentProfiledDateValidation($profiled_date)
    {
        $diff = Carbon::now()->diffInDays($profiled_date);
        return ($diff < 1) ? false : $profiled_date;
    }


    protected function studentProfiledDateValidationToDb($profiled_date)
    {

        $diff = Carbon::now()->diffInDays($profiled_date);
        return ($diff < 0) ? false : $profiled_date;
    }
}
