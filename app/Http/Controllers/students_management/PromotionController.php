<?php

namespace App\Http\Controllers\students_management;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\StudentPromotion;
use GlobalHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PromotionController extends Controller
{

    public $global;
    public function __construct()
    {

        $this->global = new GlobalHelpers();
    }

    public function index()
    {

        $data['classes'] = SchoolClass::all();

        if (auth()->user()->checkRole('Class Teacher')) {

            $assignedClasses = auth()->user()->assignedClasses();
            // $data['from_classes'] = SchoolClass::whereIn('id', $assignedClasses)->get();

            // Extract the class IDs
            $classIds = array_column($assignedClasses, 'class_id');
            // Use the extracted class IDs in the query
            $data['from_classes'] = SchoolClass::whereIn('id', $classIds)->get();

        } elseif (auth()->user()->checkRole('Admin')) {
            $data['from_classes'] = SchoolClass::all();
        }


        return View('student-management.promotion.index')->with($data);
    }

    //datatable to fetch the students to be promoted
    public function datatable(Request $request)
    {
        try {

            $counter = 1;
            $classId = $request->input('classId');

            $classes =  SchoolClass::all();
            $streams = Stream::all();

            $streamId = $request->input('streamId');
            //   dd($streamId);
            $students = Student::where('class_id', $classId)
                ->where('isgraduated', 0);


            if ($streamId) {
                $students->where('stream_id', $streamId);
            }

            $students = $students->orderBy('id', 'desc')->get();

            return DataTables::of($students)

                ->addColumn('checkbox', function ($student) {

                    $checkbox = '<div class="custom-control custom-checkbox">
                    <input type="checkbox" data-student-id="' . $student->id . '" class="custom-control-input promote-checkbox" id="customCheck-' . $student->id . '">
                    <label class="custom-control-label" for="customCheck-' . $student->id . '"></label>
                </div>';

                    return $checkbox;
                })

                ->addColumn('sn', function () use (&$counter) {
                    return $counter++;
                })

                ->addColumn('avatar', function ($student) {

                    if ($student->profile_pic) {
                        $url = asset('storage/' . $student->profile_pic);

                        $image = '
                        <div class="user-avatar bg-primary">
                        <div class="avatar-image" style="background-image: url(' . asset('' . $url . '') . ');"></div>
                        </div>
                        ';
                    } else {

                        $image = ' <div class="user-avatar bg-primary"> <span>' . $student->name_abbrv . '</span> </div>';
                    }       

                    return  '<span style="display:flex; justify-content:start; align-items:center">' . $image . ' &nbsp; ' . $student->full_name . '</span>';
                })
                ->addColumn('current_class', function ($student) {
                    if ($student->class_id) {
                        return $student->getClass->name;
                    }
                })

                ->addColumn('current_stream', function ($student) {
                    if ($student->class_id) {
                        return $student->stream->name;
                    }
                })
                ->addColumn('promotion_class', function ($student) use ($classes) {

                    $options = '';
                    foreach ($classes as $key => $class) {
                        $options .= '<option value="' . $class->id . '"> ' . $class->name . ' </option>';
                    }



                    $select = '<select data-student-id="' . $student->id . '" class="form-control select2s select_dt_promotion_class"  name="promotion_class[]">
                                <option> Select Class... </option>
                                    ' . $options . '
                                </select>';
                    return $select;
                })

                ->addColumn('promotion_stream', function ($student) use ($streams) {
                    $options = '';

                    foreach ($streams as $key => $stream) {
                        $options .= '<option value="' . $stream->id . '"> ' . $stream->name . ' </option>';
                    }
                    $select = '<select data-student-id="' . $student->id . '" class="form-control select2s select_dt_promotion_stream"  name="promotion_stream[]">
                                            <option> Select Stream... </option>
                                                ' . $options . '
                                            </select>';
                    return $select;
                })
                ->rawColumns(['avatar', 'checkbox', 'promotion_stream', 'promotion_class'])
                ->make();
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    //function to promote students

    public function promote(Request $request)
    {
        db::beginTransaction();

        // return $request->all();

        try {
            $studentsContent = $request->input('studentsContent');
            $fromStream = $request->input('fromStream');
            $fromClass = $request->input('fromClass');

            foreach ($studentsContent as $content) {

                // return $content;

                if (!intval($content['to_class'])  || !intval($content['to_stream'])) {

                    return response(['msg' => 'Students promotion Failed.', 'state' => 'fail', 'title' => 'error', 'class_id' => $fromClass, 'stream_id' => $fromStream]);
                }

                $student = Student::updateOrCreate(
                    ['id' => $content['student_id']],
                    [
                        'class_id' => $content['to_class'],
                        'stream_id' => $content['to_stream'],
                    ]
                );

                $student_promotion =  StudentPromotion::create(
                    [
                        'student_id' => $student->id,
                        'student_name' => $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname,
                        'gender' => $student->gender,
                        'from_class' => $fromClass,
                        'from_stream' => $fromStream,
                        'to_class' => $content['to_class'],
                        'to_stream' => $content['to_stream'],
                    ]
                );
            }
            db::commit();

            return response(['msg' => 'Students promotion success.', 'state' => 'done', 'title' => 'success', 'class_id' => $student_promotion->from_class, 'stream_id' => $student_promotion->from_stream]);
        } catch (QueryException $e) {

            db::rollBack();
            return response(['state' => 'error', 'title' => 'error', 'msg' => $e->errorInfo[2]]);
        }
    }

    /* CLASS TO STREAM  FILTER */

    public function fromClassFilter(Request $request)
    {

        $classID = $request->class_id;
        $streams = Stream::where('class_id', $classID)->get();

        $html = '<option>  </option>';
        foreach ($streams as $stream) {
            $html .= '<option  value = "' . $stream->id . '"> ' . $stream->name . ' </option>';
        }

        return $html;
    }

    public function toClassFilter(Request $request)
    {
        // dd($request);
        $classID = $request->class_id;
        $streams = Stream::where('class_id', $classID)->get();
        $html = '<option>  </option>';
        foreach ($streams as $stream) {
            $html .= '<option  value = "' . $stream->id . '"> ' . $stream->name . ' </option>';
        }

        return $html;
    }


    //function to manage student promotions

    public function managePromotion()
    {
        $data['headers'] = [
            // ['label' => 'Avatar', 'name' => 'avatar'],
            ['label' => 'Name', 'name' => 'name'],
            ['label' => 'Gender', 'name' => 'gender'],
            ['label' => 'From Class', 'name' => 'from_class'],
            ['label' => 'From Stream', 'name' => 'from_stream'],
            ['label' => 'Promoted Class', 'name' => 'to_class'],
            ['label' => 'Promoted Stream', 'name' => 'to_stream'],
            ['label' => 'Promotion Date', 'name' => 'promotion_date'],
            ['label' => 'Action', 'name' => 'action'],
        ];
        $data['students'] = StudentPromotion::all();

        return view('student-management.promotion.manage_promotion')->with($data);
    }

    //datatable to manage the students promotions

    public function promotionDatatable(Request $request)
    {
        try {


            $promotions = StudentPromotion::latest('created_at')->leftjoin('school_classes as from_clases', 'student_promotions.from_class', '=', 'from_clases.id')
                ->leftjoin('school_classes as to_classes', 'student_promotions.to_class', '=', 'to_classes.id')
                ->leftjoin('streams as from_stream', 'student_promotions.from_stream', '=', 'from_stream.id')
                ->leftjoin('streams as to_stream', 'student_promotions.to_stream', '=', 'to_stream.id')
                ->select('from_clases.name as from_name', 'to_classes.name as to_name', 'from_stream.name as from_stream_name', 'to_stream.name as to_stream_name', 'student_promotions.*');

            if (auth()->user()->checkRole('Class Teacher')) {
                $assignedClasses = auth()->user()->assignedClasses();
                 // extract the class ids
                 $class_id = array_column($assignedClasses, 'class_id');

                $promotions = $promotions->whereIn('student_promotions.from_class', $class_id)->groupBy('student_id')->get();
            }

            //   return $promotions->get();

            return DataTables::of($promotions)
                // ->addColumn('avatar', function ($promotion) {

                //   return  $student = Student::find($promotion->student_id);

                //     if ($student->profile_pic) {
                //         $url= asset('storage/'.$student->profile_pic);

                //         $image = '
                //         <div class="user-avatar bg-primary">
                //         <div class="avatar-image" style="background-image: url('.asset(''.$url.'').');"></div>
                //         </div>
                //         ';

                //     }else{

                //         $image = ' <div class="user-avatar bg-primary"> <span>'.$student->name_abbrv.'</span> </div>';
                //     }

                //   return  '<span style="display:flex; justify-content:start; align-items:center">'.$image.' &nbsp; '. $student->full_name.'</span>';


                // })
                ->addColumn('name', function ($promotion) {

                    return $promotion->student_name;
                })

                ->addColumn('gender', function ($promotion) {
                    if ($promotion->gender) {
                        return $promotion->gender;
                    }
                })

                ->addColumn('promotion_date', function ($promotion) {
                    $date = $promotion->created_at;
                    return  date('jS M, Y ', strtotime($date));
                })

                ->addColumn('from_class', function ($promotion) {
                    if ($promotion->from_class) {
                        return $promotion->from_name;
                    }
                })

                ->addColumn('from_stream', function ($promotion) {
                    if ($promotion->from_stream) {
                        return $promotion->from_stream_name;
                    }
                })
                ->addColumn('to_class', function ($promotion) {
                    if ($promotion->to_class) {
                        return $promotion->to_name;
                    }
                })
                ->addColumn('to_stream', function ($promotion) {
                    if ($promotion->to_stream) {
                        return $promotion->to_stream_name;
                    }
                })

                ->addColumn('action', function ($student) {
                    return '<div class="text-center">
                                <a href="#" class="text-danger reset-promotion" data-student-id="' . $student->id . '">
                                    <i class="fas fa-undo-alt text-danger"></i>
                                    Reset
                                </a>
                            </div>';
                })

                ->rawColumns(['action', 'avatar'])
                ->make();
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }



    public function resetStudentPromotion($id)
    {

        Db::beginTransaction();
        $promotionId = StudentPromotion::find($id);

        try {

            if ($promotionId) {
                Student::updateOrCreate(
                    ['id' => $promotionId->student_id],
                    [
                        'class_id' => $promotionId->from_class,
                        'stream_id' => $promotionId->from_stream,
                    ]
                );

                // add data to student promotions
                // StudentPromotion::updateOrCreate(
                //     ['id' => $promotionId->id],
                //     [
                //         'student_id' => $promotionId->student_id,
                //         'student_name' => $promotionId->student_name,
                //         'gender' => $promotionId->gender,
                //         'from_class' => $promotionId->to_class,
                //         'from_stream' => $promotionId->to_stream,
                //         'to_class' => $promotionId->from_class,
                //         'to_stream' => $promotionId->from_stream,
                //     ]
                // );

                // Remove data from student promotions
                StudentPromotion::where('id', $promotionId->id)->delete();
            }

            Db::commit();
            return response()->json(['message' => 'promotion reset successfully.']);
        } catch (\Exception $e) {

            Db::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
