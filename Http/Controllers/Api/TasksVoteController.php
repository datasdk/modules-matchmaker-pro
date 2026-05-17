<?php

namespace Modules\Tasks\Http\Controllers\Api;


use Orion\Http\Requests\Request;
use Illuminate\Http\JsonResponse;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Http\Requests\VoteRequest;
use Modules\Tasks\Services\VotingService;
use Modules\Tasks\Models\TaskInteractions;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Events\TaskGotInteraction;


class TasksVoteController extends OrionBaseController
{

    protected $request = VoteRequest::class;

    protected $model = TaskInteractions::class;

    /**
     * Store a new vote.
     *
     * @param VoteRequest $request
     * @param int $taskId
     * @return JsonResponse
     */
    public function store(Request $request)
    {

  
        $task = Tasks::findOrFail($request->task_id);

        $likeTask = Tasks::findOrFail($request->like_task_id);

  
        $match = app(VotingService::class)->vote(
            $task,
            $likeTask,
            $request->vote,
            $request->should_split ?? null,
            $request->should_reduce ?? null
        );



        event(new TaskGotInteraction($likeTask,$task));

        
        return response()->json($match);
    }



    /**
     * Reset a vote.
     *
     * @param VoteRequest $request
     * @param int $taskId
     * @return JsonResponse
     */

    // delete = reset voding

    public function destroy(Request $request,...$args)
    {
      
        $user = $request->user();


        $task = Tasks::findOrFail($args[0]);


        if ($user->id !== $task->user_id) {
            abort(403, 'Du er ikke tilknyttet denne sag.');
        }

  
        $result = app(VotingService::class)->resetVotes($task);

        return response()->json($result);

    }
    
}
