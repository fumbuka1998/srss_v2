<?php

namespace App\Http\Controllers\students_management;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Alumni;
use Illuminate\Support\Facades\DB;


use App\Mail\SignInMail;
use App\Models\Attachment;
use App\Models\Club;
use App\Models\Contact;
use App\Models\ContactPerson;
use App\Models\House;
use App\Models\ModelHasPermission;
use App\Models\ModelHasRole;
use App\Models\Permission;
use App\Models\Religion;
use App\Models\ReligionSect;
use App\Models\Role;
use App\Models\Stream;
use App\Models\User;
use App\Models\UserHasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use stdClass;



class GraduationController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }

    public function index()
    {
        $data['classes'] = $graduate = SchoolClass::whereIn('id', [6, 7])->get();

        if (auth()->user()->checkRole('Class Teacher')) {
            // Get the assigned classes of the user
            $assignedClasses = auth()->user()->assignedClasses();

            // $data['graduates'] = SchoolClass::whereIn('id', $assignedClasses)
            //     ->whereIn('id', [6, 7])
            //     ->get();
            
            // Extract the class IDs
            $classIds = array_column($assignedClasses, 'class_id');
            // Use the extracted class IDs in the query
            $data['graduates'] = SchoolClass::whereIn('id', $classIds)
                ->whereIn('id', [6, 7])
                ->get();
        } elseif (auth()->user()->checkRole('Admin')) {
            $data['graduates'] = SchoolClass::whereIn('id', [6, 7])->get();
        }

        return View('student-management.graduated.index')->with($data);
    }


    // allumni index
    public function allumniIndex()
    {
        $data['activeTab'] = 'allumni';
        $data['houses'] = House::all();
        $data['classes'] = SchoolClass::all();
        $data['streams'] = Stream::all();
        $data['religions'] = Religion::all();
        $data['religion_sects'] = ReligionSect::all();
        $data['clubs'] = Club::all();
        $data['roles'] = Role::all();

        return view('student-management.graduated.allumniIndex')->with($data);
    }

    //datatable to fetch the students to be graduated
    public function datatable(Request $request)
    {
        try {
            $classId = $request->input('classId');

            $students = Student::where('class_id', $classId)
                ->where('isgraduated', 0)
                ->orderBy('id', 'desc');

            return DataTables::of($students)
                ->addColumn('name', function ($student) {
                    return $student->full_name;
                })
                // ->addColumn('avatar', function ($student) {
                //     // Your avatar column logic here
                // })

                ->addColumn('avatar', function ($student) {

                    if ($student->profile_pic) {
                        $url = asset('storage/' . $student->profile_pic);

                        $image = ' <div
        style="
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        overflow: hidden;
        background-image: url(\'' . $url . '\');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        ">
        </div>';
                    } else {

                        $image = '

        <div
        style="
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        overflow: hidden;
        "
        >
        <img
        style="
        width: 100%;
        height: auto;
        " src="' . asset('assets/img/icon_avatar.jpeg') . '" alt="Profile Picture">
        </div>';
                    }
                    return $image;
                })
                ->addColumn('class', function ($student) {
                    if ($student->class_id) {
                        return $student->getClass->name;
                    }
                })

                ->addColumn('check', function ($student) {
                    return '<span style="display:center; justify-content:center; align-items:center">
                        <input type="checkbox" class="graduate-checkbox" data-student-id="' . $student->id . '">
                    </span>';
                })
                ->rawColumns(['check', 'avatar'])
                ->make();
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    // alumni datatable
    public function allumni_datatable(Request $request)
    {
        // return $request;

        $query = Alumni::all();

        // Global Search
        if (!empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('admission_no', 'like', '%' . $search . '%')
                    ->orWhere('graduation_year', 'like', '%' . $search . '%')
                    ->orWhere('achievement', 'like', '%' . $search . '%');
            });
        }

        // Class filter
        if (!empty($request->class_id)) {
            $query->where('class_id', $request->class_id);
        }

        // Stream filter
        if (!empty($request->stream_id)) {
            $query->where('stream_id', $request->stream_id);
        }

        // Achievement filter
        if (!empty($request->achievement)) {
            $query->where('achievement', $request->achievement);
        }




        return DataTables::of($query)
            ->addColumn('full_name', function ($alumni) {
                return $alumni->name;
            })
            ->addColumn('graduation_class', function ($alumni) {
                if ($alumni->class_id) {

                    return $alumni->getClass->name;
                }
            })
            ->addColumn('graduation_stream', function ($alumni) {

                if ($alumni->stream_id) {

                    return $alumni->stream->name;
                }
            })
            ->addColumn('graduation_year', function ($alumni) {
                return $alumni->graduation_year;
            })
            ->addColumn('achievements', function ($alumni) {
                return $alumni->achievement;
            })
            // ->addColumn('action', function ($alumni) {
            //     return '<a href="#" data-uuid="' . $alumni->id . '" class="btn btn-danger btn-sm delete disabled">Delete</a>';
            // })
            ->addColumn('action', function ($alumni) {
                return '<span style="display:center; justify-content:center; align-items:center">
               <button  style="color:white" data-uuid="' . $alumni->id . '" type="button"  class="btn btn-danger btn-sm delete disabled"><i class="fa fa-trash"></i></button>
                   </span>';
            })
            ->rawColumns(['action']) // Allows HTML in the action column
            ->make(true);
    }


    //function to graduate students

    public function graduate_og(Request $request)
    {
        db::beginTransaction();

        try {
            $studentIds = $request->input('studentIds');
            // dd($studentIds);
            foreach ($studentIds as $studentId) {
                $student = Student::find($studentId);
                //  dd($student);
                if ($student) {
                    // Update isgraduated column in student table
                    $student->update(['isgraduated' => 1]);

                    // Student::updateOrCreate(
                    //     ['id'=>$student->id],
                    //     [
                    //         'isgraduated' => 1
                    //     ]
                    // );

                    $achievement = ($student->class_id == 6) ? 'O-LEVEL CERTIFICATE' : 'A-LEVEL CERTIFICATE';

                    // add data to alumni
                    Alumni::updateOrCreate(
                        ['student_id' => $student->id],
                        [
                            // 'student_id' => $student->id,
                            'name' => $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname,
                            'academic_year' => $student->registration_date,
                            'achievement' => $achievement,
                        ]
                    );
                }
            }
            Db::commit();

            return response()->json(['message' => 'Students graduated successfully.']);
        } catch (\Exception $e) {
            Db::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function graduate(Request $request)
    {
        db::beginTransaction();

        try {
            $studentIds = $request->input('studentIds');
            // dd($studentIds);
            foreach ($studentIds as $studentId) {
                $student = Student::find($studentId);
                // dd($student);
                if ($student) {
                    $student->update(['isgraduated' => 1]);

                    $achievement = ($student->class_id == 6) ? 'O-LEVEL CERTIFICATE' : 'A-LEVEL CERTIFICATE';
                    $year = \Carbon\Carbon::now()->year;


                    // add data to alumni
                    Alumni::updateOrCreate(
                        ['student_id' => $student->id],
                        [
                            'admission_no' => $student->admission_no,
                            'name' => $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname,
                            'academic_year' => $student->registration_date,
                            'achievement' => $achievement,
                            'class_id' => $student->class_id,
                            'stream_id' => $student->stream_id,
                            'graduation_year' => $year

                        ]
                    );
                }
            }
            Db::commit();

            return response()->json(['message' => 'Students graduated successfully.']);
        } catch (\Exception $e) {
            Db::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
