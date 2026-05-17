<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Tasks;
use Carbon\Carbon;
use Modules\Tasks\Services\TaskStatusCodeService as Status;


class TaskSplitService
{

    public function split(Tasks $splitTask, Tasks $compareTask)
    {

        $taskService = app(TaskService::class);

        
        if($splitTask->amount <= 0){ return null; }

 
        $split = [
            'merge_backward' => $this->mergeBackwardSplit($splitTask, $compareTask),
            'allocation' => $this->allocationSplit($splitTask, $compareTask),
            'residual' => $this->residualSplit($splitTask, $compareTask),
            'merge_forward' => $this->mergeForwardSplit($splitTask, $compareTask),
        ];
        
  
        if(!$split['allocation']){ return null; }


        $job = $compareTask->isJob() ? $compareTask : $split['allocation'];

        $application = $split['allocation']->isApplication() ? $split['allocation'] : $compareTask;


       // $this->hire($job, $application); den skal ikke hyre i split

        $this->splitAndDeleteTask($splitTask);



        return [
            'merge_backward' => $split['merge_backward'],
            'allocation' => $split['allocation'],
            'residual' => $split['residual'],
            'merge_forward' => $split['merge_forward'],
            'job' => $compareTask->load('available'),
            'application' => $splitTask->load('available')
        ];

    }




    private function mergeBackwardSplit(Tasks $splitTask, Tasks $compareTask)
    {

        $amount = $splitTask->amount;

        if ($compareTask->available->from->isSameDay($splitTask->available->from) || $compareTask->available->from < $splitTask->available->from) {
            
            return null;

        }

        $from = $splitTask->available->from;
        $to = $compareTask->available->from->subDay();

    
        return $this->createSplit($splitTask, $compareTask, [
            'from' => $from,
            'to' => $to,
            'status' => Status::LIVE,
            'amount' => $amount
        ]);

    }



    private function allocationSplit(Tasks $splitTask, Tasks $compareTask)
    {
        
        if($splitTask->amount <= $compareTask->amount){

           $amount = $splitTask->amount;

        } else {
 
            $amount = $compareTask->amount;

        }
        

        $status = $splitTask->available->from->greaterThan(now()) ? Status::PENDING : Status::ONGOING;

   
        return $this->createSplit($splitTask, $compareTask, [
            'from' => max($splitTask->available->from, $compareTask->available->from),
            'to' => min($splitTask->available->to, $compareTask->available->to),
            'status' => $status,
            'amount' => $amount
        ]);

    }


    private function residualSplit(Tasks $splitTask, Tasks $compareTask)
    {

        if($splitTask->amount <= $compareTask->amount){

            return null;
           //$amount = $compareTask->amount - $splitTask->amount;

        } else {
 
            $amount = $splitTask->amount - $compareTask->amount;

        }
      

        return $this->createSplit($splitTask, $compareTask, [
            'from' => max($splitTask->available->from, $compareTask->available->from),
            'to' => min($splitTask->available->to, $compareTask->available->to),
            'status' => Status::LIVE,
            'amount' => $amount
        ]);

    }


    private function mergeForwardSplit(Tasks $splitTask, Tasks $compareTask)
    {

        $amount = $splitTask->amount;


        if ($compareTask->available->to->isSameDay($splitTask->available->to) || $compareTask->available->to > $splitTask->available->to) {
           
            return null;

        }


        return $this->createSplit($splitTask, $compareTask, [
            'from' => $compareTask->available->to->addDay(),
            'to' => $splitTask->available->to,
            'status' => Status::LIVE,
            'amount' => $amount
        ]);


    }


    private function createSplit(Tasks $splitTask, Tasks $compareTask, array $customparams)
    {
      
        $amount = $customparams['amount'];


        if ($amount <= 0) {
            return null;
        }


        $params = [
            'parent_id' => $splitTask->id,
            'amount' => $amount,
            'status' => $customparams['status'] ?? 'live'
        ];


        $task = $this->copy($splitTask, $params);
        
        $task->set_available([
            'from' => $customparams['from'],
            'to' => $customparams['to']
        ]);

    

        return $task->load("available");

    }


    private function copy(Tasks $splitTask, array $params = [])
    {

        return app(TaskService::class)->copyTask($splitTask, $params);

    }


    private function isMatching(Tasks $splitTask, Tasks $compareTask)
    {

        return app(MatchService::class)->isMatching($splitTask, $compareTask);

    }


    private function hire(Tasks $compareTask, Tasks $splitTask)
    {

        app(HireService::class)->hireTask($compareTask, $splitTask);

    }

    private function splitAndDeleteTask(Tasks $task){

        $task->update(['status' => Status::SPLITTED]);
       // $task->delete();
   
    }

  

}