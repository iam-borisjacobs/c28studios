<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $project->load('service');

        $relatedProjects = Project::query()
            ->with('service')
            ->published()
            ->whereKeyNot($project->id)
            ->ordered()
            ->limit(2)
            ->get();

        return view('projects.show', [
            'project' => $project,
            'relatedProjects' => $relatedProjects,
            'settings' => HomeController::resolveSettings(),
        ]);
    }
}

