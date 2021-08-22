<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\ProjectAnnouncement;
use App\Http\Controllers\Controller;
use App\Model\ProjectAnnouncementComment;

class projectAnnouncementController extends Controller
{
    // Getting announcements
    public function index($id)
    {
        $announcements = ProjectAnnouncement::where('project_id',$id)->orderBy('id','desc')->with('announcementComment.user')->get();
        return $announcements;
    }
    
    // Storing announcements
    public function store(Request $request)
    {
        $announcements = ProjectAnnouncement::create([
            'project_id'    =>  $request->project_id,
            'title'         =>  $request->title,
            'description'   =>  $request->description
        ]);
        return $this->index($request->project_id);
    }
    
    // Updating announcement
    public function update(Request $request)
    {
        $announcements = ProjectAnnouncement::findOrFail($request->id)->update([
            'title'         =>  $request->title,
            'description'   =>  $request->description
        ]);
        return $this->index($request->project_id);
    }
    
    // Deleting announcement
    public function delete(Request $request)
    {
        $announcements = ProjectAnnouncement::findOrFail($request->id)->delete();
        return $this->index($request->project_id);
    }
    
    // Adding Announcement Comment
    public function storeComment(Request $request)
    {
        $announcements = ProjectAnnouncementComment::create([
            'user_id'                   =>  auth()->user()->id,
            'project_announcement_id'   =>  $request->announcement_id,
            'text'                      =>  $request->text
        ]);
        return $this->index($request->project_id);
    }
    
    // Deleting Announcement Comment
    public function deleteComment(Request $request)
    {
        $announcements = ProjectAnnouncementComment::findOrFail($request->id)->delete();
        return $this->index($request->project_id);
    }
}
