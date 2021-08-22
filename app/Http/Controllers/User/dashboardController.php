<?php

namespace App\Http\Controllers\User;

use App\Model\Project;
use App\Model\ProjectTask;
use App\Model\ProjectMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class dashboardController extends Controller
{
    public function index()
    {
        $data = ProjectMember::where('user_id', auth()->user()->id)->select('project_id')->get(); // Finding project for the user
        $projects = []; // Keeping the projects in an array
        foreach ($data as $key) { 
            array_push($projects, $key->project_id);
        }
        // Finding on-Going projects
        $ongoing  = Project::whereIn('id', $projects)->where('dateFrom', '<=', \Carbon\Carbon::now())->get();
        // Finding Upcoming projects
        $upcoming  = Project::whereIn('id', $projects)->where('dateFrom', '>', \Carbon\Carbon::now())->get();
        // Finding user projects
        $userProjects = auth()->user()->projects;
        // Finding user tasks
        $projectsInvolved = Project::whereIn('id', $projects)->orderBy('id','desc')->limit(10)->get();
        // dd($tasks[0]->taskMembers);
        return view('user.dashboard',compact('userProjects','ongoing','upcoming','projectsInvolved'));
    }
}
