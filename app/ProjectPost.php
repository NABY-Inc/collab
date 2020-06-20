<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPost extends Model
{
    protected $guarded = [];

    // Relating to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relating to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relating to Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relating Post Resources
    public function postResources()
    {
        return $this->hasMany(PostResource::class);
    }
}
