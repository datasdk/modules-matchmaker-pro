<?php

namespace Modules\Tasks\Services;

use Illuminate\Support\Facades\Log;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskInteractions;
use Modules\Tasks\Models\Matches;
use Modules\Tasks\Services\MatchService;



class VotingService
{


    public function vote(Tasks $task, Tasks $likeTask, $vote = 1, $shouldSplit = null, $shouldReduce = null)
    {


        if ($task->id == $likeTask->id) {

            throw new \Exception('Self-vote attempt prevented', ['task_id' => $task->id]);

        }


        $likeTask->load("user");

        $matchService = app(MatchService::class);


        if ($vote) {


            try {


                $task->like($likeTask, $vote, $shouldSplit, $shouldReduce);


                if ($likeTask->isLiking($task)) {
                 
                    if ($matchResult = $matchService->makeMatch($task, $likeTask)) {
                        
                        return $matchResult;

                    } else {
 
                        throw new \Exception("Failed to create match despite reciprocal like");

                    }


                }


            } catch (\Exception $e) {

                throw $e;

            }


        } else {


            try {

                $task->dislike($likeTask);

            } catch (\Exception $e) {

                throw $e;

            }


        }

        
        return [
            "match" => false
        ];

    }


    public function resetVotes(Tasks $task)
    {

      
        try {
      
            $result = TaskInteractions::where('task_id', $task->id)
                ->where('like', 0)
                ->delete();
            
            return $result;


        } catch (\Exception $e) {

            throw $e;

        }

    }

    

}