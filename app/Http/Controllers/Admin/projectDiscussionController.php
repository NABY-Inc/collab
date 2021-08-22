<?php

namespace App\Http\Controllers\Admin;

use App\Model\ProjectFile;
use Illuminate\Http\Request;
use App\Model\ProjectDiscussion;
use App\Http\Controllers\Controller;

class projectDiscussionController extends Controller
{
    // Getting discussions
    public function index($id)
    {
        $discussions = ProjectDiscussion::where('project_id',$id)->orderBy('id','desc')->with('user','projectFiles')->get();
        return $discussions;
    }

    // Storing tasks
    public function store(Request $request)
    {
        $discussion = ProjectDiscussion::create([
            'project_id'    =>  $request->project_id,
            'user_id'       =>  auth()->user()->id,
            'chat'          =>  $request->message,
        ]);
        
        // Checking if user selects a file
        if ($request->hasFile('attachments')) {
            $files = $this->uploadFiles($request);
            // Looping through the uploaded file names and save in DB
            foreach($files as $file){
                ProjectFile::create([
                    'project_discussion_id' => $discussion->id, 
                    'project_id'            => $request->project_id, 
                    'user_id'               => auth()->user()->id,
                    'file'                  => $file 
                ]);
            }
        }

        return $this->index($request->project_id);
    }

    // upload Files
    public function uploadFiles($request)
    {
        $uploadedImages = []; // Will keep file names in here
        // Looping through individual file
        foreach ($request->file('attachments') as $file) {
            $uploadedImages[] = $this->uploadFile($file);
        }
        return $uploadedImages;
    }

    // Upload Individual File
    public function uploadFile($file)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalName, PATHINFO_FILENAME);
        $fileName = "PCS_FILE" . "_" . rand(1000,9000) . "." . $extension;
        $file->move(public_path('uploads/project_files'), $fileName); // Saving file into directory

        return $fileName;
    }

    // Deleting task
    public function delete(Request $request)
    {
        $files = ProjectFile::where('project_discussion_id',$request->id)->get();
        if (count($files) > 0) {
            for ($i=0; $i < count($files); $i++) { 
                \File::delete(public_path('uploads/project_files/'.$files[$i]->file));
            }
        }
        ProjectFile::where('project_discussion_id',$request->id)->delete(); // Deleting File from DB
        ProjectDiscussion::findOrFail($request->id)->delete();  // Deleting Discussion from DB
        return $this->index($request->project_id);
    }
}
