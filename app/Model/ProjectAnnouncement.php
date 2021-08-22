<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProjectAnnouncement extends Model
{
    protected $guarded = [];

    /* Get the project that owns the announcement */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relating to Project announcement comment
    public function announcementComment()
    {
        return $this->hasMany(ProjectAnnouncementComment::class);
    }
}
