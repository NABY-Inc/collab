<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    /* Get the user that owns the Project */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relating to Project Members
    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }
    
    // Relating to Project Announcements
    public function announcements()
    {
        return $this->hasMany(ProjectAnnouncement::class);
    }
    
    // Relating to Project notes
    public function notes()
    {
        return $this->hasMany(ProjectNote::class);
    }
    
    // Relating to Project tasks
    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }
}
