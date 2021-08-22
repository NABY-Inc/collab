<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class dashboardController extends Controller
{
    public function index()
    {
        $ongoing     = Project::whereDate('dateFrom', '<=', \Carbon\Carbon::now())->count();
        $upcoming    = Project::whereDate('dateFrom', '>', \Carbon\Carbon::now())->count();
        $systemUsers = User::orderBy('role','desc')->get();
        return view('admin.dashboard',compact('systemUsers','ongoing','upcoming'));
    }
}
