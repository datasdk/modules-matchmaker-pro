<?php

namespace Modules\Tasks\Http\Controllers;

use Orion\Http\Requests\Request;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Models\TaskRatingsScheduled;
use Modules\Tasks\Http\Requests\TaskRatingsScheduledRequest;
use Illuminate\Http\RedirectResponse;

class TaskRatingSchedulesController extends OrionBaseController
{
    protected $model = TaskRatingsScheduled::class;
    protected $request = TaskRatingsScheduledRequest::class;

    protected $includes = [
        'task',
        'user',
        'taskForRate',
        "taskForRate.company",
        "taskForRate.address",
        "taskForRate.categories",
        "taskForRate.user.contact",
        "taskForRate.available",
        "task"
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\View\View
     */
    public function index(Request $req, ...$args)
    {
        return view('tasks::ratings-scheduled.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\View\View
     */
    public function create(Request $req, ...$args)
    {
        $taskRatingScheduled = new TaskRatingsScheduled();
        return view('tasks::ratings-scheduled.create', compact('taskRatingScheduled'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\View\View
     */
    public function edit(Request $req, ...$args)
    {
        $id = $args[0] ?? null;
        $taskRatingScheduled = TaskRatingsScheduled::findOrFail($id);
        return view('tasks::ratings-scheduled.edit', compact('taskRatingScheduled'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\View\View
     */
    public function show(Request $req, ...$args)
    {
        $id = $args[0] ?? null;
        $taskRatingScheduled = TaskRatingsScheduled::with($this->includes)->findOrFail($id);
        return view('tasks::ratings-scheduled.show', compact('taskRatingScheduled'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $req, ...$args)
    {
        $validated = $req->validated();
        
        $taskRatingScheduled = TaskRatingsScheduled::create($validated);
        
        return redirect()->route('promatch.ratings-scheduled.index')
            ->with('success', 'Task rating schedule created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $req, ...$args)
    {
        $id = $args[0] ?? null;
        $validated = $req->validated();
        
        $taskRatingScheduled = TaskRatingsScheduled::with(['task', 'taskForRate'])->findOrFail($id);

        if (!$taskRatingScheduled->task || !$taskRatingScheduled->taskForRate) {
            return back()->withErrors([
                'error' => 'Rating must have an associated task and taskForRate.'
            ]);
        }

        $taskRatingScheduled->update($validated);
        
        return redirect()->route('promatch.ratings-scheduled.index')
            ->with('success', 'Task rating schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $req
     * @param mixed ...$args
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $req, ...$args)
    {
        $id = $args[0] ?? null;
        $taskRatingScheduled = TaskRatingsScheduled::findOrFail($id);
        $taskRatingScheduled->delete();
        
        return redirect()->route('promatch.ratings-scheduled.index')
            ->with('success', 'Task rating schedule deleted successfully.');
    }
}