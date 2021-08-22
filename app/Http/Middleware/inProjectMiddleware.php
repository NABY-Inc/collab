<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Project;
use App\Model\ProjectMember;

class inProjectMiddleware
{
    public function handle($request, Closure $next)
    {
        // Getting project id
        $project_id = auth()->user()->role == 1 ? $request->route('admin_project'): $request->route('project');
        // Check if user is in that project
        $project = ProjectMember::where('user_id',auth()->user()->id)->where('project_id',$project_id)->get();
        // Check if user is in the upcoming project
        $upcoming_projects  = Project::where('id', $project_id)->whereDate('dateFrom', '>', \Carbon\Carbon::now())->get();
        // if not so 404
        if (count($project) < 1) {
            return abort(404);
        }
        // When project is in upcoming
        else if(count($upcoming_projects) > 0){
             return auth()->user()->role == 1 ? redirect()->route('admin-upcoming',$project_id) : redirect()->route('upcoming',$project_id);
        }
        // if so, proceed 
        return $next($request);
    }

}
