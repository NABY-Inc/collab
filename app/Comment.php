<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    // Relating to Project Post
    public function projectPost()
    {
        return $this->belongsTo(ProjectPost::class);
    }

    // Relating to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relating to comment Resource
    public function commentResources()
    {
        return $this->hasMany(CommentResource::class);
    }
}
