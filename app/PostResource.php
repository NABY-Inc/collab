<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostResource extends Model
{
    protected $guarded = [];

    protected $table = 'post_resource';

    // Relating to Post
    public function projectPost()
    {
        return $this->belongsTo(ProjectPost::class);
    }

    // Relating to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
