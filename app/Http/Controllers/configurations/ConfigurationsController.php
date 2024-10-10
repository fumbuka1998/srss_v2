<?php

namespace App\Http\Controllers\configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{



    public function index(){


        return view('configurations.index');

    }
}
