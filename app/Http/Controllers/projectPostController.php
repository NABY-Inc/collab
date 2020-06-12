<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class projectPostController extends Controller
{
    //All Posts
    public function allPosts()
    {
        $posts = \App\ProjectPost::with('user','comments', 'comments.user')->where('project_id', request()->id)->orderBy('id', 'DESC')->paginate(3);
        return $posts;
    }

    // User Posts
    public function userPosts()
    {
        $posts = \App\ProjectPost::where('project_id',request()->id)->where('user_id',auth()->user()->id)->with('user','comments','comments.user')->orderByDesc('id')->paginate(1);
        return $posts;
    }

    // Create Post
    public function createPost(Request $request)
    {
        if (empty($request->edit_id)) {
            $data = $request->validate([
                'title'   => 'required',
                'message' => 'required',
            ]);
            // Adding project id to data to be stored
            $data = $this->array_push_assoc($data,'project_id',$request->id);
            auth()->user()->projectPosts()->create($data);
        }else{
            // Updating Project Post
            \App\ProjectPost::findOrFail($request->edit_id)->update([
                'title'   => $request->title,
                'message' => $request->message
            ]);
        }
        return true;
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

    // Custom function to add project id
    public function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }
}
