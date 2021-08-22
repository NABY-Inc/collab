<?php

namespace App\Model;

use App\User;
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
    
    // relating to project note
    public function notes()
    {
        return $this->hasMany(ProjectNote::class);
    }

    
}
