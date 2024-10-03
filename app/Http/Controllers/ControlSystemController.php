<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControlSystemController extends Controller
{
    public function index(){
        return view('admin.camera-control.index');
    }
}
