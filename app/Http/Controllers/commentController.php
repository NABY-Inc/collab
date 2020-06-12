<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class commentController extends Controller
{
    //Create comment
    public function addComment(Request $request)
    {
        $data = $request->validate([
            'project_post_id' => 'required',
            'message'         => 'required'
        ]);
        $response = auth()->user()->comments()->create($data);
        return $response;
    }

    //Delete comment
    public function deleteComment(Request $request)
    {
        \App\Comment::findOrFail($request->id)->delete();
        return true;
    }
}
