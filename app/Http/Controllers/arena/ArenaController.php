<?php

namespace App\Http\Controllers\arena;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ArenaController extends Controller
{




public function subjects(){

    $data['years'] = AcademicYear::all();
    $data['classes'] = SchoolClass::all();

    return view('arena.subjects')->with($data);


}






}
