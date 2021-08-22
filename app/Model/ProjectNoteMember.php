<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectNoteMember extends Model
{
    protected $guarded = [];

    // relating to project member
    public function projectNote()
    {
        return $this->belongsTo(ProjectNote::class);
    }
    
    // relating to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
