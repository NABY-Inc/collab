<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
    public function index()
    {
        $systemUsers = 'App\User'::orderBy('id','desc')->limit(10)->get();
        $ongoingProjects = \App\Project::where('dateFrom', '<=', \Carbon\Carbon::now())->orWhere('dateTo', '=', \Carbon\Carbon::now())->get();
        $upcomingProjects = \App\Project::where('dateFrom', '>', \Carbon\Carbon::now())->get();
        return view('admin.dashboard', compact('systemUsers','ongoingProjects','upcomingProjects'));
    }

    public function project()
    {
        return view('admin.dashboard');
    }
}
