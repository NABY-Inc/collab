<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectAnnouncementComment extends Model
{
    protected $guarded = [];

    // Relating to announcements
    public function announcement()
    {
        return $this->belongsTo(ProjectAnnouncement::class);
    }

    // Relating to users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
