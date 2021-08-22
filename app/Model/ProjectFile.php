<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $guarded = [];

    // relating to project member
    public function projectDiscussion()
    {
        return $this->belongsTo(ProjectDiscussion::class);
    }

    /* Get the user that owns the Project */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
