<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship between user and project
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Relating to project Posts
    public function projectPosts()
    {
        return $this->hasMany(ProjectPost::class);
    }

    // Relating to comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    

    // Relating a Post Resource
    public function postResouces()
    {
        return $this->hasMany(PostResource::class);
    }

    // Relating to comment Resource
    public function commentResources()
    {
        return $this->hasMany(CommentResource::class);
    }
}
