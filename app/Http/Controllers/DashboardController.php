<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    public function index()
    {

        //  return "hapa";

        // Fetch data
        // $studentsByClass = Student::select('class_id', DB::raw('count(*) as total'))
        //     ->groupBy('class_id')
        //     ->pluck('total', 'class_id');

        // Fetch data for students by class
        $studentsByClass = Student::select('class_id', DB::raw('count(*) as total'))
            ->groupBy('class_id')
            ->pluck('total', 'class_id');

        // Convert class IDs to names
        $classNames = SchoolClass::whereIn('id', $studentsByClass->keys())->pluck('name', 'id');
        // $studentsByClassName = $studentsByClass->mapWithKeys(function ($value, $key) use ($classNames) {
        //     return [$classNames[$key] => $value];
        // });
        $studentsByClassName = $studentsByClass->mapWithKeys(function ($value, $key) use ($classNames) {
            // Check if $classNames array has the key $key
            if (isset($classNames[$key])) {
                return [$classNames[$key] => $value];
            } else {
                // Handle case where $key is not found in $classNames
                return ['Unknown Class' => $value]; // Example fallback value
            }
        });

        // $studentsByStream = Student::select('stream_id', DB::raw('count(*) as total'))
        //     ->groupBy('stream_id')
        //     ->pluck('total', 'stream_id');


        // $studentsByStream = Student::join('streams', 'students.stream_id', '=', 'streams.id')
        //     ->join('school_classes', 'students.class_id', '=', 'school_classes.id')
        //     ->select(DB::raw('CONCAT(school_classes.name, "_", streams.name) AS class_stream_name'), DB::raw('count(*) as total'))
        //     ->groupBy('class_stream_name')
        //     ->pluck('total', 'class_stream_name');

        // $studentsByClass = Student::join('school_classes', 'students.class_id', '=', 'school_classes.id')
        //     ->join('streams', 'students.stream_id', '=', 'streams.id')
        //     ->select(DB::raw('CONCAT(school_classes.name, "_", streams.name) AS class_stream_name'), DB::raw('count(*) as total'))
        //     ->groupBy('class_stream_name')
        //     ->pluck('total', 'class_stream_name');

        // Count number of male students
        $maleCount = Student::where('gender', 'male')->count();

        // Count number of female students
        $femaleCount = Student::where('gender', 'female')->count();

        $genderCount = [
            'maleCount'=>$maleCount,
            'femaleCount'=>$femaleCount
        ];

        return view('dashboard.home', compact('studentsByClassName','genderCount'));

        // return view('dashboard.home');
    }
}
