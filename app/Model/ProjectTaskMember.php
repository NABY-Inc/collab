<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskMember extends Model
{
    protected $guarded = [];

    // relating to project member
    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class);
    }
    
    // relating to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
