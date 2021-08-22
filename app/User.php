<?php

namespace App;

use App\Model\Project;
use App\Model\ProjectFile;
use App\Model\ProjectTask;
use App\Model\ProjectMember;
use App\Model\ProjectDiscussion;
use App\Model\ProjectNoteMember;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','pass_reset','contact','profile','active','role'
    ];

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

    /* Get all of the projects for the User */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Relating to Project Members
    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    // Relating to Project announcement comment
    public function announcementComment()
    {
        return $this->hasMany(ProjectAnnouncementComment::class);
    }

    // relating to project note
    public function notes()
    {
        return $this->hasMany(ProjectNote::class);
    }
    
    // relating to project note member
    public function noteMembers()
    {
        return $this->hasMany(ProjectNoteMember::class);
    }
    
    // relating to project tasks
    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }
    
    // relating to project task member
    public function taskMembers()
    {
        return $this->hasMany(ProjectTaskMember::class);
    }

    // relating to project discussion
    public function discussions()
    {
        return $this->hasMany(ProjectDiscussion::class);
    }
    
    // relating to project files
    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }
}
