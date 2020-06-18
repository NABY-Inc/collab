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
            $data = $this->validateData();

            // Saving image and adding to data array if any
            if ($request->hasFile('image')){
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('uploads/project_thumbnails'), $fileName);
                $data = $this->array_push_assoc($data, 'image', $fileName);
            }

            // Storing Data
            $data = auth()->user()->projects()->create($data);
            // Getting Project Id
            $project_id = $data->id;
            // Keeping Project members
            $members = array_map('intval', explode(',', $request->team));
            $members = array_unique($members); 
            // Looping through members to store for project members
            foreach ($members as $key => $member) {
                \App\ProjectMember::create(['project_id' => $project_id, 'user_id' => $member]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
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

            // Validating Data
            $data = $this->validateData();

            // Saving image and adding to data array if any
            if ($request->hasFile('image')){
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                // If image already exists
                if ($request->oldImage != 'default.jpg' && \File::exists(public_path('uploads/project_thumbnails/'.$request->oldImage))) {
                    \File::delete(public_path('uploads/project_thumbnails/'.$request->oldImage));
                    // return 'Yes Projet have its Selected Image';
                }
                $request->file('image')->move(public_path('uploads/project_thumbnails'), $fileName);
                $data = $this->array_push_assoc($data, 'image', $fileName);
            }

            // Updating Project
            $data = \App\Project::findOrFail($id)->update($data);
            // Keeping Project members
            $members = $request->team; 
            // Checking if user has selected new members to join the project
            $members = array_map('intval', explode(',', $request->team));
            $members = array_unique($members); 
            if (end($members) > 0) {
                // Looping through members to store for project members
                foreach ($members as $key => $member) {
                    \App\ProjectMember::create(['project_id' => $id, 'user_id' => $member]);
                }
            }
            return true;
        } catch (\Exception $e) {
            return false; // Expected error will be invalid image or image size
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

    // validating data
    public function validateData()
    {
        $data = request()->validate([
            'title'       => 'required',
            'category'    => 'required',
            'code'        => 'required',
            'priority'    => 'required',
            'dateFrom'    => 'required',
            'dateTo'      => 'required',
            'image'       => 'sometimes|required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required',
            'progress'    => 'sometimes',
        ]);
        return $data;
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

    // Custom function to add image
    public function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }
}
