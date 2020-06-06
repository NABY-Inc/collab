<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
    public function index()
    {
        $systemUsers = 'App\User'::orderBy('id','desc')->get();
        return view('admin.dashboard', compact('systemUsers'));
    }

    public function project()
    {
        return view('admin.dashboard');
    }
}
