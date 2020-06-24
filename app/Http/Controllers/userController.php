<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class userController extends Controller
{
    //Returning user Dashboard
    public function index()
    {
        $data = \App\ProjectMember::where('user_id', auth()->user()->id)->select('project_id')->get(); // Finding project for the user
        $projects = []; // Keeping the projects in an array
        foreach ($data as $key) { 
            array_push($projects, $key->project_id);
        }
        // Finding on-Going projects
        $ongoingProjects   = \App\Project::whereIn('id', $projects)->where('dateFrom', '<=', \Carbon\Carbon::now())->get();
        // Finding Upcoming projects
        $upcomingProjects  = \App\Project::whereIn('id', $projects)->where('dateFrom', '>', \Carbon\Carbon::now())->get();
        // Finding user projects
        $userProjects = auth()->user()->projects;
        return view('user.dashboard', compact('ongoingProjects','upcomingProjects','userProjects'));
    }
}
