<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class projectController extends Controller
{
    /* Display a listing of the resource. */
    public function index()
    {
        // dd(\Carbon\Carbon::parse(now())->format('Y-m-d'));
        $data = \App\ProjectMember::where('user_id', auth()->user()->id)->select('project_id')->get();
        $projects = [];
        foreach ($data as $key) {
            array_push($projects, $key->project_id);
        }
        // Finding on-Going projects
        $ongoing_projects   = \App\Project::whereIn('id', $projects)->where('dateFrom', '<', \Carbon\Carbon::now())->orderByDesc('id')->paginate(4);

        // Finding Upcoming projects
        $upcoming_projects  = \App\Project::whereIn('id', $projects)->where('dateFrom', '>', \Carbon\Carbon::now())->orderByDesc('id')->paginate(8);

        //Returning project page per role
        if (auth()->user()->role == 1){
            return view('admin.project.index', compact('ongoing_projects','upcoming_projects'));
        }elseif (auth()->user()->role == 2){
            return view('user.project.index', compact('ongoing_projects','upcoming_projects'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /* Store a newly created resource in storage. */
    public function store(Request $request)
    {
        try {
            // validating data
            $data = $request->validate([
                'title'       => 'required',
                'category'    => 'required',
                'code'        => 'required',
                'priority'    => 'required',
                'dateFrom'    => 'required',
                'dateTo'      => 'required',
                'description' => 'required',
            ]);
            // Storing Data
            $data = auth()->user()->projects()->create($data);
            // Getting Project Id
            $project_id = $data->id;
            // Keeping Project members
            $members = $request->team; 
            // Looping through members to store for project members
            foreach (end($members) as $member) {
                \App\ProjectMember::create(['project_id' => $project_id, 'user_id' => $member]);
            }
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //Returning project Detail page per role
        if (auth()->user()->role == 1){
            return view('admin.project.show');
        }elseif (auth()->user()->role == 2){
            return view('user.project.show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Get all users
    public function allMembers()
    {
        $users = User::select('id','name')->where('active', 1 )->get();
        return $users;
    }
}
