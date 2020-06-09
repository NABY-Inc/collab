<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $guarded = [];

    // Relating to project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // relating to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
