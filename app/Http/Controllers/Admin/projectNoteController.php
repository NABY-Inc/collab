<?php

namespace App\Http\Controllers\Admin;

use App\Model\ProjectNote;
use Illuminate\Http\Request;
use App\Model\ProjectNoteMember;
use App\Http\Controllers\Controller;

class projectNoteController extends Controller
{
    // Getting announcements
    public function index($id)
    {
        $notes = ProjectNote::where('project_id',$id)->orderBy('id','desc')->with('user','noteMembers.user')->get();
        return $notes;
    }
    
    // Storing Notes
    public function store(Request $request)
    {
        $note = ProjectNote::create([
            'project_id' =>  $request->project_id,
            'user_id'    =>  auth()->user()->id,
            'title'      =>  $request->title,
            'text'       =>  $request->text
        ]);
        ProjectNoteMember::create(['project_note_id' =>  $note->id,'user_id' => auth()->user()->id]);
        return $this->index($request->project_id);
    }
    
    // Storing Note Members
    public function storeMembers(Request $request)
    {
        // Get New Members
        $membersIds = explode(',',$request->members);
        // Get Old Members
        $oldMembers = ProjectNoteMember::where('project_note_id',$request->project_note_id)->get();
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
                $member = ProjectNoteMember::where(['project_note_id'=>$request->project_note_id, 'user_id'=>$differenceIds[$i]])->get();
                if (count($member) > 0) { // If member is in already, means he has been removed so we delete
                    ProjectNoteMember::where(['project_note_id'=>$request->project_note_id,'user_id'=>$differenceIds[$i]])->delete();
                }else{ // If member dont exist we create 
                    ProjectNoteMember::create(['project_note_id'=>$request->project_note_id,'user_id'=>$differenceIds[$i]]);
                }
            }
        }
        return $this->index($request->project_id);
    }
}
