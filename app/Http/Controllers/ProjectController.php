<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['address'])->paginate();

        return view('projects.index', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('projects.create', [
            'statusOptions' => ProjectStatus::options()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = new Project($request->safe([
            'name',
            'description',
            'status',
            'budget',
            'client_id'
        ]));

        $project->fill([
            'start_date' => Carbon::createFromFormat('d/m/Y', $request->safe(['start_date'])),
            'end_date' => Carbon::createFromFormat('d/m/Y', $request->safe(['end_date'])),
            'user_id' => $request->user()->id
        ]);

        $project->address()->create($request->safe()->only([
            'address_1',
            'address_2',
            'suburb',
            'postcode',
            'state',
            'country'
        ]));

        $project->save();

        return redirect()->route('projects.show', ['project' => $project])->with('success', 'Project has been created successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', [
            'project' => $project,
            'statusOptions' => ProjectStatus::options()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
