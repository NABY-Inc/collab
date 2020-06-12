<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    // Relating to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relating to Project Members
    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    // Relating to Project Posts
    public function projectPosts()
    {
        return $this->hasMany(ProjectPost::class);
    }
}
