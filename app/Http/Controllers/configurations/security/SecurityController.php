<?php

namespace App\Http\Controllers\configurations\security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    //

    public function index(){
        return view('configurations.security.index');
    }
}
