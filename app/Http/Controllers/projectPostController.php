<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class projectPostController extends Controller
{
    //All Posts
    public function allPosts()
    {
        $posts = \App\ProjectPost::with('user','comments', 'comments.user')->where('project_id', request()->id)->orderBy('id', 'DESC')->paginate(2);
        return $posts;
    }

    // User Posts
    public function userPosts()
    {
        $posts = \App\ProjectPost::where('project_id',request()->id)->where('user_id',auth()->user()->id)->with('postResources','user','comments','comments.user')->orderByDesc('id')->paginate(1);
        return $posts;
    }

    // Create and update Post
    public function postDriver(Request $request)
    {
        try {
            // If create
            if (empty($request->edit_id)) {
                $this->store($request);
            }
            // if Edit
            else{
                $this->update($request);
            }
            return true;
        } catch (\Exception $e) {
            return false; // Invalid data passed
        }
        
    }

    // Saving Post
    public function store($request)
    {
        $data = $request->validate([
            'title'   => 'required',
            'message' => 'required',
        ]);
        // Adding project id to data to be stored
        $data = $this->array_push_assoc($data,'project_id',$request->id);
        // Creationg Post
        $projectPost = auth()->user()->projectPosts()->create($data);
        // Validating file
        $request->validate(['uploads.*' => 'mimes:pdf,doc,docx,ppt,pptx,xls,txt,png,jpeg,jpg,gif,zip|max:10000']); // File should be max of 10MB
        // Calling uploadFiles method
        $files = $this->uploadFiles($request);
        // Looping through the uploaded file names and save in DB
        foreach($files as $file){
            auth()->user()->postResouces()->create(['file' => $file, 'project_post_id' => $projectPost->id]);
        }
    }

    // Updating Post
    public function update($request)
    {
        // Updating Project Post
        \App\ProjectPost::findOrFail($request->edit_id)->update([
            'title'   => $request->title,
            'message' => $request->message
        ]);
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
        $file->move(public_path('uploads/post_resource'), $fileName); // Saving file into directory

        return $fileName;
    }

    // Delete Post
    public function deletePost(Request $request)
    {
        $comments = \App\Comment::where('project_post_id', $request->edit_id)->get();
        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                \App\Comment::where('id', $comment->id)->delete();
            }
        }
        \App\ProjectPost::findOrFail($request->edit_id)->delete();
        return true;
    }

    // Delete Resource
    public function deleteResource(Request $request)
    {
        \File::delete(public_path('uploads/post_resource/'.$request->url));
        \App\PostResource::findOrFail($request->resource_id)->delete();
        return true;
    }

    // Custom function to add project id
    public function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }
}
