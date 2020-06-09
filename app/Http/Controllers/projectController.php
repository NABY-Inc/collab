<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class projectController extends Controller
{
    /* Display a listing of the resource. */
    public function index()
    {
        $data = \App\ProjectMember::where('user_id', auth()->user()->id)->select('project_id')->get(); // Finding project for the user
        $projects = []; // Keeping the projects in an array
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

    /* Display the specified resource. */
    public function show($id)
    {
        $project = \App\Project::find($id);
        //Returning project Detail page per role
        if (auth()->user()->role == 1){
            return view('admin.project.show', compact('project'));
        }elseif (auth()->user()->role == 2){
            return view('user.project.show', compact('project'));
        }
    }

    /* Update the specified resource in storage. */
    public function update(Request $request, $id)
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
                'progress'    => 'sometimes',
            ]);
            // Updating Project
            $data = \App\Project::findOrFail($id)->update($data);
            // Keeping Project members
            $members = $request->team; 
            // Checking if user has selected new members to join the project
            if (count($members) > 0) {
                // Looping through members to store for project members
                foreach (end($members) as $member) {
                    \App\ProjectMember::create(['project_id' => $id, 'user_id' => $member]);
                }
            }
            return true;
        } catch (\Exception $e) {
            return $e;
        }
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

    // Choosing non selected members
    public function nonSelectedMembers($id)
    {
        // Getting users of that project
        $data = \App\ProjectMember::where('project_id',$id)->select('user_id')->get();
        $members_of_that_project = [];
        // Looping through to store their ID's into array
        foreach ($data as $key) {
            array_push($members_of_that_project, $key->user_id);
        }
        // Now selecting members who are not in the project
        $members_not_part_of_project = User::select('id','name')->where('active', 1 )->whereNotIn('id', $members_of_that_project)->get();
        return $members_not_part_of_project;
    }

    // Removing Project Member
    public function removeMember($id)
    {
        \App\ProjectMember::findOrFail($id)->delete();
        return true;
    }
}
