<?php

namespace Modules\Tasks\Http\Controllers\Api;

use Orion\Http\Requests\Request;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Http\Requests\MatchRequest;
use Modules\Tasks\Models\Matches;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\VotingService;
use Modules\Tasks\Services\HireService;



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

 

    public function hire(Request $request, $taskId, $hireTaskId)
    {
  
        $task = Tasks::findOrFail($taskId);

        $hireTask =  Tasks::findOrFail($hireTaskId);

        $match = app(HireService::class)->hireTask($task, $hireTask);

        return response()->json($match);

    }

    
    public function reject(Request $request, $taskId, $rejectTaskId)
    {

        $task = Tasks::findOrFail($taskId);

        $rejectTask =  Tasks::findOrFail($rejectTaskId);

        $match = app(MatchService::class)->rejectMatch($task, $rejectTask);

        return response()->json($match);


    }


    public function vote(Request $req,  $id)
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


        if($user_id != $task->user_id){ abort(403, "Du er ikke tilknyttet denne sag"); }
        
        if($task->id == $likeTask->id){

            abort(400,"Opslag må ikke have ens id");

        }


        $vote = $req->vote;

        $should_split = $req->has("should_split") ? $req->should_split : null;
        
        $should_reduce = $req->has("should_reduce") ? $req->should_reduce : null;


        $match = app(VotingService::class)->vote($task, $likeTask,$vote,$should_split,$should_reduce);

        

        return response()->json($match);
        
    }


    public function resetVotes(Request $request, $id)
    {
        
        $task = Tasks::findOrFail($id);

        return response()->json( app(VotingService::class)->resetVotes($task));

    }



}
