<?php

namespace Modules\Tasks\Http\Controllers;

use Orion\Http\Requests\Request;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Models\Matches;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Http\Requests\MatchRequest;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Services\VotingService;
use Modules\Tasks\Services\HireService;
use Illuminate\Http\RedirectResponse;

class MatchController extends OrionBaseController
{
    protected $model = Matches::class;
    protected $keyname = "uid";
    protected $request = MatchRequest::class;
    
    protected $includes = [
        'task', 'task.categories', 'task.available', 'task.company', 'task.user',
        'hires', 'hired', 'match', 'match.categories', 'match.available',
        'match.company', 'match.user', 'messages', 'last_message', 
        'participants.messageable', 'participants', 'other', 'calendar'
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
        return view('tasks::matches.index');
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
        // Opret et tomt match objekt til formularen
        $match = new Matches();
        
        return view('tasks::matches.create', compact('match'));
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
        $match = Matches::findOrFail($id);
        
        return view('tasks::matches.edit', compact('match'));
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
        $match = Matches::with([
            'task', 
            'task.addresses',
            'task.contacts',
            'task.skills',
            'task.user',
            'task.company',
            'matchedTask',
            'matchedTask.addresses',
            'matchedTask.contacts',
            'matchedTask.skills',
            'matchedTask.user',
            'matchedTask.company'
        ])->findOrFail($id);
        
        return view('tasks::matches.show', compact('match'));
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
        // Validering håndteres af MatchRequest gennem OrionBaseController
        $validated = $req->validated();
        
        // Opret ny match
        $match = Matches::create($validated);
        
        return redirect()->route('promatch.matches.index')
            ->with('success', 'Match created successfully.');
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
        
        // Validering håndteres af MatchRequest gennem OrionBaseController
        $validated = $req->validated();
        
 
        // Find og opdater match
        $match = Matches::findOrFail($id);

        $match->update($validated);
        
        return redirect()->route('promatch.matches.index')
            ->with('success', 'Match updated successfully.');
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
        $match = Matches::findOrFail($id);
        $match->delete();
        
        return redirect()->route('promatch.matches.index')
            ->with('success', 'Match deleted successfully.');
    }

    /**
     * Hire a task.
     *
     * @param Request $request
     * @param int $taskId
     * @param int $hireTaskId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hire(Request $request, $taskId, $hireTaskId)
    {
        $task = Tasks::findOrFail($taskId);
        $hireTask = Tasks::findOrFail($hireTaskId);
        
        $match = app(HireService::class)->hireTask($task, $hireTask);
        
        return redirect()->back()
            ->with('success', 'Task hired successfully.');
    }

    /**
     * Reject a match.
     *
     * @param Request $request
     * @param int $taskId
     * @param int $rejectTaskId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $taskId, $rejectTaskId)
    {
        $task = Tasks::findOrFail($taskId);
        $rejectTask = Tasks::findOrFail($rejectTaskId);
        
        $match = app(MatchService::class)->rejectMatch($task, $rejectTask);
        
        return redirect()->back()
            ->with('success', 'Match rejected successfully.');
    }

    /**
     * Vote on a match.
     *
     * @param Request $req
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote(Request $req, $id)
    {
        $req->validate([
            "like_task_id" => "required|exists:tasks,id",
            "vote" => "required|int",
            "should_split" => "sometimes|int",
            "should_reduce" => "sometimes|int"
        ]);

        $user_id = $req->user()->id;
        $task = Tasks::findOrFail($id);
        $likeTask = Tasks::findOrFail($req->like_task_id);

        if ($user_id != $task->user_id) {
            return back()->withErrors(['error' => 'Du er ikke tilknyttet denne sag']);
        }
        
        if ($task->id == $likeTask->id) {
            return back()->withErrors(['error' => 'Opslag må ikke have ens id']);
        }

        $vote = $req->vote;
        $should_split = $req->has("should_split") ? $req->should_split : null;
        $should_reduce = $req->has("should_reduce") ? $req->should_reduce : null;

        $match = app(VotingService::class)->vote($task, $likeTask, $vote, $should_split, $should_reduce);

        return redirect()->back()
            ->with('success', 'Vote submitted successfully.');
    }

    /**
     * Reset votes for a task.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetVotes(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);
        app(VotingService::class)->resetVotes($task);
        
        return redirect()->back()
            ->with('success', 'Votes reset successfully.');
    }
}