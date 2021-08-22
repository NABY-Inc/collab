<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectNote extends Model
{
    protected $guarded = [];

    // relating to project member
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    // relating to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // relating to project note member
    public function noteMembers()
    {
        return $this->hasMany(ProjectNoteMember::class);
    }
}
