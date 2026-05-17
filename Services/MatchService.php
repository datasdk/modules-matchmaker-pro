<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Matches;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskInteractions;
use Modules\Tasks\Models\Hires;
use Modules\Tasks\Events\MatchCreated;
use Carbon\Carbon;

class MatchService
{
    public function getMatchId(Tasks $task1, Tasks $task2): ?string
    {

        $id1 = $task1->id ?? null;
        $id2 = $task2->id ?? null;

        if (!$id1 || !$id2) {
            return null;
        }

        $taskIds = [trim($id1), trim($id2)];
        sort($taskIds, SORT_NUMERIC);

        return "task." . implode(".", $taskIds);

    }

    public function makeMatch(Tasks $task, Tasks $likeTask)
    {
  
        $match_id = $this->makeMatchId($task->id, $likeTask->id);


        Matches::updateOrCreate([
            "uid" => $match_id,
            "task_id" => $task->id,
            "match_with_task_id" => $likeTask->id
        ]);

        Matches::updateOrCreate([
            "uid" => $match_id,
            "task_id" => $likeTask->id,
            "match_with_task_id" => $task->id
        ]);



        if ($this->isMatching($task, $likeTask)) {
        
            
            $result = [
                "match" => true,
                "match_id" => $match_id,
                "tasks" => [
                    "job" => $task->isJob() ? $task : $likeTask,
                    "application" => $task->isApplication() ? $task : $likeTask,
                    "me" => $task->refresh(),
                    "other" => $likeTask->refresh()
                ],
                
            ];


            $result["events"] = event(new MatchCreated($result));;


            return $result;

        }


        return false;

    }


    public function makeMatchId(int $id1,int $id2){

        if(!$id1 || !$id2) return null;

        $taskIds = [$id1, $id2];

        sort($taskIds, SORT_NUMERIC);

        return "task." . implode(".", $taskIds);

    }


    public function isMatching(?Tasks $task1, ?Tasks $task2)
    {

        if(!$task1 || !$task2){ return false; }
        

        return 
            Matches::where('task_id', $task1->id)->where('match_with_task_id', $task2->id)->exists() && 
            Matches::where('task_id', $task2->id)->where('match_with_task_id', $task1->id)->exists();

    }

    
    public function rejectMatch($taskId, $rejectTaskId)
    {

        Matches::where('task_id', $taskId)->where('match_with_task_id', $rejectTaskId)->delete();
        Matches::where('task_id', $rejectTaskId)->where('match_with_task_id', $taskId)->delete();
        TaskInteractions::where('task_id', $taskId)->where('likeable_task_id', $rejectTaskId)->update(['like' => 0]);

        return Hires::where('task_id', $taskId)->where('hired_task_id', $rejectTaskId)->delete();

    }


    public function getPotentialMatches(Tasks $task){


        $specifikations = [
            "type" => $task->type,
            "user_id" => $task->user_id,
            "categories" => $task->categories->pluck("id"),
            "price" => $task->price,
            "available" => [
                "from" => $task->available?->from,
                "to" => $task->available?->to,
            ],
           
        ];


        if($task->address){

            $specifikations["position"] = [
                "lng" => $task->address->lng,
                "lat" => $task->address->lat,
                "radius" => 100
            ];

        }
        
        
        return $task->searchMatches($specifikations)->count();


    }


    public function updatePotentialMatches(Tasks $task){

        $matches = $this->getPotentialMatches($task);

        $task->potential_matches = $matches;

        $task->save();

        return $matches;

    }


    
    public function getMatchQuality(Tasks $task, Tasks $compareTask): int
    {

        // Tjek for manglende availability data
        if (
            !$task->available ||
            !$compareTask->available ||
            !$task->available->from ||
            !$task->available->to ||
            !$compareTask->available->from ||
            !$compareTask->available->to
        ) {

            return 3;

        }
    

        $myTask = [
            'type'   => $task->type,
            'amount' => $task->amount,
            'from'   => Carbon::parse($task->available->from)->startOfDay(),
            'to'     => Carbon::parse($task->available->to)->endOfDay(),
        ];

        $task = [
            'type'   => $compareTask->type,
            'amount' => $compareTask->amount,
            'from'   => Carbon::parse($compareTask->available->from)->startOfDay(),
            'to'     => Carbon::parse($compareTask->available->to)->endOfDay(),
        ];
            

        // Sammenlign mængde
        $isAmountMatching = $myTask['amount'] <= $task['amount'];
    

        // Sammenlign periode
        $isPeriodMatching =
            $myTask['from']->greaterThanOrEqualTo($task['from']) &&
            $myTask['to']->lessThanOrEqualTo($task['to']);
    


        if ($isPeriodMatching && $isAmountMatching) {

            return 1;

        }
    

        if ($isPeriodMatching || $isAmountMatching) {

            return 2;

        }
    

        return 3;


    }
    

}