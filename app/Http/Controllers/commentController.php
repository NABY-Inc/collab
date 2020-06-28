<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class commentController extends Controller
{
    //Create comment
    public function addComment(Request $request)
    {
        $data = $request->validate([
            'project_post_id' => 'required',
            'message'         => 'required'
        ]);
        // Validating file
        $request->validate(['uploads.*' => 'sometimes|mimes:pdf,doc,docx,ppt,pptx,xls,txt,png,jpeg,jpg,gif,zip|max:10000']); // File should be max of 10MB
        // Saving comment message
        $comment = auth()->user()->comments()->create($data);
        // Calling uploadFiles method
        $files = $this->uploadFiles($request);
        // Looping through the uploaded file names and save in DB
        foreach($files as $file){
            auth()->user()->commentResources()->create(['file' => $file, 'comment_id' => $comment->id]);
        }
        return true;
    }

    // upload Files
    public function uploadFiles($request)
    {
        $uploadedImages = []; // Will keep file names in here
        // Checking if user selects a file
        if ($request->hasFile('uploads')) {
            // Looping through individual file
            foreach ($request->file('uploads') as $file) {
                $uploadedImages[] = $this->uploadFile($file);
            }
        }
        return $uploadedImages;
    }

    // Upload Individual File
    public function uploadFile($file)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalName, PATHINFO_FILENAME);
        $fileName = str::slug($fileNameOnly) . "-" . time() . "." . $extension;
        $file->move(public_path('uploads/comment_resource'), $fileName); // Saving file into directory

        return $fileName;
    }

    //Delete comment
    public function deleteComment(Request $request)
    {
        \App\Comment::findOrFail($request->id)->delete();
        // Deleting comment resources
        $resources = \App\CommentResource::where('comment_id', $request->id)->get();
        if (count($resources) > 0) {
            foreach ($resources as $resource) {
                \File::delete(public_path('uploads/comment_resource/'.$resource->file));
                \App\CommentResource::where('id', $resource->id)->delete();
            }
        }
        return true;
    }

    // Delete comment Resource
    public function deleteResource(Request $request)
    {
        \File::delete(public_path('uploads/comment_resource/'.$request->url));
        \App\CommentResource::where('id',$request->resource_id)->delete();
        return $request->resource_id;
    }
}
