<?php

namespace Modules\Tasks\Listeners;


use Modules\Tasks\Events\TaskHired;
use Modules\Tasks\Services\MatchCalendarService;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Models\Tasks;


class CreateCalendarEntryFromMatch
{


    public function handle(TaskHired $event)
    {
        

        $job = $event->job;

        $application = $event->application;

        
        $task_id = $this->findMyTask($job,$application);


        if(!$task_id){

            return null;

        }


        $matchId = app(MatchService::class)->getMatchId($job, $application);



        $data = [
            'match_id' => $matchId,
            'job_id' => $job->id,
            'application_id' => $application->id,
            'task_id' => $task_id->id
        ];


        $entries = [];

        foreach ([$job, $application] as $task) {

            $entries[$task->type] = app(MatchCalendarService::class)->create($task, $data, $matchId);
            
        }

        
        return $entries;

    }




    private function findMyTask(Tasks $job,Tasks $application){

        $user_id = request()->user()->id;


        if($job->user->id == $user_id){

            return $job;

        }

        else if($application->user->id == $user_id){

            return $application;
            
        } else {

            return null;

        }


    }

}
