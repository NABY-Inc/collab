<?php

namespace App\Http\Controllers\User;

use App\Model\ProjectTask;
use Illuminate\Http\Request;
use App\Model\ProjectTaskMember;
use App\Http\Controllers\Controller;

class projectTaskController extends Controller
{
    // Getting tasks
    public function index($id)
    {
        $tasks = ProjectTask::where('project_id',$id)->orderBy('id','desc')->with('user','taskMembers.user')->get();
        return $tasks;
    }

    // Storing tasks
    public function store(Request $request)
    {
        $task = ProjectTask::create([
            'project_id'    =>  $request->project_id,
            'user_id'       =>  auth()->user()->id,
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            'start'         =>  $request->start,
            'due'           =>  $request->due,
        ]);
        // Putting Members Id into array
        $membersIds = !empty($request->members) ? explode(',',$request->members) : [];
        if (count($membersIds) > 0) {
            for ($i=0; $i < count($membersIds); $i++) { 
                ProjectTaskMember::create(['project_task_id' => $task->id,'user_id' => $membersIds[$i]]);
            }
        }
        return $this->index($request->project_id);
    }

    // Updating task
    public function update(Request $request)
    {
        ProjectTask::findOrFail($request->id)->update([
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            'start'         =>  $request->start,
            'due'           =>  $request->due,
        ]);
        return $this->index($request->project_id);
    }
    
    // Deleting task
    public function delete(Request $request)
    {
        ProjectTask::findOrFail($request->task_id)->delete();
        ProjectTaskMember::where('project_task_id',$request->task_id)->delete();
        return $this->index($request->project_id);
    }
    
    // Storing task Members
    public function storeMembers(Request $request)
    {
        // Get New Members
        $membersIds = explode(',',$request->members);
        // Get Old Members
        $oldMembers = ProjectTaskMember::where('project_task_id',$request->project_task_id)->get();
        $oldMembersIds = [];
        for ($i=0; $i < count($oldMembers); $i++) { 
            array_push($oldMembersIds,$oldMembers[$i]->user_id);
        }
        // If old member is in new members wont delete
        $difference = count($membersIds) > count($oldMembersIds) ? array_diff($membersIds,$oldMembersIds) : array_diff($oldMembersIds,$membersIds);
        if (count($difference) > 0) {   // If there is a difference
            $differenceIds = []; // Array to store the difference IDs
            foreach ($difference as $ids) {
                array_push($differenceIds,$ids); // Storing IDs into the array
            }
            for ($i=0; $i < count($differenceIds); $i++) { // We loop and get the members
                $member = ProjectTaskMember::where(['project_task_id'=>$request->project_task_id, 'user_id'=>$differenceIds[$i]])->get();
                if (count($member) > 0) { // If member is in already, means he has been removed so we delete
                    ProjectTaskMember::where(['project_task_id'=>$request->project_task_id,'user_id'=>$differenceIds[$i]])->delete();
                }else{ // If member dont exist we create 
                    ProjectTaskMember::create(['project_task_id'=>$request->project_task_id,'user_id'=>$differenceIds[$i]]);
                }
            }
        }
        return $this->index($request->project_id);
    }
    
    // Toggling task status
    public function toggleComplete(Request $request)
    {
        ProjectTask::findOrFail($request->task_id)->update(['status'=>!$request->status]);
        return $this->index($request->project_id);
    }
}
