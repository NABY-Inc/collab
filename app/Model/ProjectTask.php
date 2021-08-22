<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $guarded = [];

    /* Get the user that owns the Project */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // relating to project 
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // relating to project task member
    public function taskMembers()
    {
        return $this->hasMany(ProjectTaskMember::class);
    }
}
