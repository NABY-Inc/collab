<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Model\Project;
use App\Model\ProjectMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class projectController extends Controller
{
    // Calling the is user in project middleware
    public function __construct()
    {
        $this->middleware("is_user_in_project")->only(['show']);
    }
    
    /* Display a listing of the resource. */
    public function index()
    {
        // Getting user projects
        $userProjects = $this->userProjects();
        // Finding on-Going projects
        $ongoing_projects   = $this->ongoing_upcoming_projects($userProjects)[0];
        // Finding Upcoming projects
        $upcoming_projects  = $this->ongoing_upcoming_projects($userProjects)[1];
        return view('user.project.index',compact('ongoing_projects','upcoming_projects'));
    }

    /* Store a newly created resource in storage. */
    public function store(Request $request)
    {
        // validating data
        $data = $this->validateData();

        // Saving image and adding to data array if any
        if ($request->hasFile('image')){
            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/project_thumbnails'), $fileName);
            $data = $this->array_push_assoc($data, 'flyer', $fileName);
        }
        
        // Adding Project Code
        $data = $this->array_push_assoc($data, 'code', $this->generateCode());
        // Storing Data
        $data = auth()->user()->projects()->create($data);
        // Getting Project Id
        $project_id = $data->id;
        // Keeping Project members
        $members = array_map('intval', explode(',', $request->team));
        $members = array_unique($members);
        // Adding project host to project
        array_push($members,auth()->user()->id);
        
        // Looping through members to store for project members
        foreach ($members as $member) {
            ProjectMember::create(['project_id' => $project_id, 'user_id' => $member]);
        }
        // Getting user projects
        $userProjects = $this->userProjects();
        return [$this->ongoing_upcoming_projects($userProjects)[0], $this->ongoing_upcoming_projects($userProjects)[1]];
    }

    /* Display the specified resource. */
    public function show($id)
    {
        $project = Project::where('id',$id)->with('user','members.user','notes.noteMembers')->first();
        return view('user.project.show',compact('project'));
    }
    
    /* Display the specified resource. */
    public function showUpcoming($id)
    {
        $project = Project::where('id',$id)->first();
        return view('user.project.showUpcoming',compact('project'));
    }

    /* Update the specified resource in storage. */
    public function update(Request $request, $id)
    {
        // validating data
        $data = $this->validateData();

        // Updating image and adding to data array if any
        if ($request->hasFile('image')){
            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
            if ($request->old_image != 'default.jpg') { // Deleting old flyer if not default
                \File::delete(public_path('uploads/project_thumbnails/'.$request->old_image));
            }
            $request->file('image')->move(public_path('uploads/project_thumbnails'), $fileName);
            $data = $this->array_push_assoc($data, 'flyer', $fileName);
        }

        // Find project and update
        Project::findOrFail($id)->update($data);

        // Get previous project members
        $members = ProjectMember::where('project_id',$id)->get();
        $oldMembers = [];
        for ($i=0; $i < count($members); $i++) { 
            array_push($oldMembers,$members[$i]->user_id);
        }
        $team = explode(',',$request->team); // Converting team into array
        // Adding project host to old members
        array_push($team,auth()->user()->id);
        // if team is more we compare old <-to- team else team <-to- old
        $difference = count($team) > count($oldMembers) ? array_diff($team,$oldMembers) : array_diff($oldMembers,$team);
        if (count($difference) > 0) {   // If there is a difference
            $differenceIds = []; // Array to store the difference IDs
            foreach ($difference as $ids) {
                array_push($differenceIds,$ids); // Storing IDs into the array
            }
            for ($i=0; $i < count($differenceIds); $i++) { // We loop and get the members
                $member = ProjectMember::where(['project_id' => $id, 'user_id' => $differenceIds[$i]])->get();
                if (count($member) > 0) { // If member is in already, means he has been removed so we delete
                    ProjectMember::where(['project_id'=>$id,'user_id' => $differenceIds[$i]])->delete();
                }else{ // If member dont exist we create 
                    ProjectMember::create(['project_id'=>$id,'user_id' => $differenceIds[$i]]);
                }
            }
        }
        // Getting user projects
        $userProjects = $this->userProjects();
        return [$this->ongoing_upcoming_projects($userProjects)[0], $this->ongoing_upcoming_projects($userProjects)[1]];
    }

    /* Remove the specified resource from storage. */
    public function destroy($id)
    {
        Project::findOrFail($id)->delete(); // Deleting Project
        ProjectMember::where('project_id',$id)->delete();    // Deleting Associated Project members
        // Getting user projects and returning them
        $userProjects = $this->userProjects();
        return [$this->ongoing_upcoming_projects($userProjects)[0], $this->ongoing_upcoming_projects($userProjects)[1]];
    }

    // validating data
    public function validateData()
    {
        $data = request()->validate([
            'title'       => 'required',
            'category'    => 'required',
            'dateFrom'    => 'required',
            'dateTo'      => 'required',
            'description' => 'required',
            'progress'    => 'sometimes',
            'color'       => 'sometimes',
            'freeze'      => 'sometimes',
        ]);
        return $data;
    }

    // Getting user associated projects
    public function userProjects()
    {
        // Finding project for the user
        $projectsInvolved = ProjectMember::where('user_id', auth()->user()->id)->select('project_id')->get(); 
        // Array to hold the project IDs
        $projectIDs = [];
        // Looping and storing priject Id's 
        foreach ($projectsInvolved as $project) { 
            array_push($projectIDs, $project->project_id);
        }
        return $projectIDs;
    }

    // Getting ongoing and upcoming projects
    public function ongoing_upcoming_projects($userProjects)
    {
        // Finding on-Going projects
        $ongoing_projects   = Project::whereIn('id', $userProjects)->whereDate('dateFrom', '<=', \Carbon\Carbon::now())->with('user','members.user')->orderByDesc('id')->get();
        // Finding Upcoming projects
        $upcoming_projects  = Project::whereIn('id', $userProjects)->whereDate('dateFrom', '>', \Carbon\Carbon::now())->with('user','members.user')->orderByDesc('id')->get();
        return [$ongoing_projects,$upcoming_projects];
    }

    // Get all users
    public function allMembers()
    {
        $users = User::select('id','name')->where('id','!=',auth()->user()->id)->where('active',1)->get();
        return $users;
    }

    // Generating Project Code
    public function generateCode()
    {
        // Generate the random code
        for ($i=0; $i < 100 ; $i++) { 
            $new_code = 'PCS'.mt_rand(1000000000, 9999999999).'project';
            if(Project::where('code',$new_code)->first()){ // Check if code is already created
                continue; // If code created, generate code again
            }else{
                return $new_code; // Keep generating untill code not in DB and return the code
            break;
            };
        }
    }

    // Joining Project
    public function joinProject()
    {
        $project = Project::where('code',request()->code)->first();   // Getting the project
        if (!empty($project)) { // If project exist
            // Getting member
            $member = ProjectMember::where('user_id',auth()->user()->id)->where('project_id',$project->id)->first(); 
            if(!empty($member)){ // If user exist as member
                return 111; // Error for already exist
            }else{
                ProjectMember::create(['project_id'=>$project->id,'user_id'=>auth()->user()->id]); // Adding user to project
                // Getting user projects and returning them
                $userProjects = $this->userProjects();
                return [$this->ongoing_upcoming_projects($userProjects)[0], $this->ongoing_upcoming_projects($userProjects)[1]];
            }
        }else{
            return 212; // Invalid Code
        }
    }
    
    // Freeze Project
    public function toggleFreeze()
    {
        // Toggling project freeze
        Project::where('id',request()->project_id)->update(['freeze' => !request()->freeze]);
        // Getting user projects and returning them
        $userProjects = $this->userProjects();
        if (request()->has('show')) {
            return Project::where('id',request()->project_id)->with('user','members.user')->first();
        } else {
            return [$this->ongoing_upcoming_projects($userProjects)[0], $this->ongoing_upcoming_projects($userProjects)[1]];
        }
        
    }

    // Custom function to add default password
    public function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }
}
