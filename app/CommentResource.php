<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentResource extends Model
{
    protected $guarded = [];

    // Relating to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relating to comments
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
