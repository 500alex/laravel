<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $title = "Admin page";
        return view('admin')->with('title',$title);
    }
}
