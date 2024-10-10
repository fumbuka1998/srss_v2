<?php

namespace App\Http\Controllers\academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    //
    public function index(){


        return view('configurations.academic.index');

    }
}
