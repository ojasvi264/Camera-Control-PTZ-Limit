<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CloneController extends Controller
{
    public function create(){
        return view('admin.clone.create');
    }
}
