<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectDiscussion extends Model
{
    protected $guarded = [];

    /* Get the user that owns the Project */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relating to project member
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // relating to project files
    public function projectFiles()
    {
        return $this->hasMany(ProjectFile::class);
    }
}
