<?php

namespace App\Http\Controllers\User;

use App\Model\ProjectFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class projectFileController extends Controller
{
    // Getting Files
    public function index($id)
    {
        $files = ProjectFile::where('project_id',$id)->with('user')->get();
        return $files;
    }
    // Uploading Files
    public function upload(Request $request)
    {
        // Checking if user selects a file
        if ($request->hasFile('attachments')) {
            $files = $this->uploadFiles($request);
            // Looping through the uploaded file names and save in DB
            foreach($files as $file){
                ProjectFile::create([
                    'project_id'            => $request->project_id, 
                    'user_id'               => auth()->user()->id,
                    'file'                  => $file 
                ]);
            }
        }
        \File::delete(public_path('uploads/project_files/'.$request->file));
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
    // Deleting Files
    public function delete(Request $request)
    {
        $files = ProjectFile::where('id',$request->id)->delete();
        \File::delete(public_path('uploads/project_files/'.$request->file));
        return $this->index($request->project_id);
    }
}
