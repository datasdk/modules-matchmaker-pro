<?php

namespace Modules\Tasks\Listeners;

use Modules\Tasks\Events\MatchCreated;
use Modules\Tasks\Services\TaskSplitService;
use Modules\Tasks\Services\MatchService;
use Illuminate\Support\Facades\Log;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\TaskService;
use Modules\Tasks\Services\HireService;
use Modules\Tasks\Services\TaskStatusCodeService as Status;
use Modules\Tasks\Services\TaskReductionService;

class HandleSplitTasks
{
    public $task1;
    public $task2;
    public $tasks;

    public function handle(MatchCreated $event)
    {

        $this->task1 = $event->task1->withoutRelations();

        $this->task2 = $event->task2->withoutRelations();


        if (!$this->isValid($this->task1, $this->task2)) {

            return;

        }



        $this->tasks = [$this->task1, $this->task2];

        $splitCode = $this->getSplitTypeCode($this->tasks);


        if (!$splitCode) {

            Log::warning("HandleSplitTasks: Ugyldig split-kode genereret", [
                'task1_id' => $this->task1->id,
                'task2_id' => $this->task2->id,
                'evaluated_code' => implode("", $this->getCodeArray($this->tasks)),
            ]);

            return false;

        }

        $result = $this->handleSplitOrReduceWithCode($splitCode, $this->task1, $this->task2);


        return $result;

    }


    protected function getSplitTypeCode(array $tasks)
    {

        $allowedCodes = ["NN", "NS", "NR", "RR", "SS", "RS"];

        $codeArray = $this->getCodeArray($tasks);

        sort($codeArray);

        $code = implode("", $codeArray);

        return in_array($code, $allowedCodes) ? $code : null;

    }

    protected function getCodeArray(array $tasks)
    {

        $codeArray = [];

        foreach ($tasks as $task) {

            if ($task->shouldSplit()) {

                $codeArray[] = "S";

            } elseif ($task->shouldReduce()) {

                $codeArray[] = "R";

            } else {

                $codeArray[] = "N";

            }

        }

        return $codeArray;

    }

    protected function split(Tasks $actionTask, Tasks $compareTask)
    {

        $splitResult = app(TaskSplitService::class)->split($actionTask, $compareTask);

        if (!$splitResult) {

            Log::error("HandleSplitTasks: Task split failed", [
                'actionTask_id' => $actionTask->id,
                'compareTask_id' => $compareTask->id,
            ]);

            return null;

        }

        return $splitResult;

    }


    protected function reduce(Tasks $actionTask, Tasks $compareTask, bool $justBothDates)
    {

        $reduceResult = app(TaskReductionService::class)->reduce($actionTask, $compareTask, $justBothDates);

        if (!$reduceResult) {

            Log::warning("HandleSplitTasks: Task reduce failed", [
                'actionTask_id' => $actionTask->id,
                'compareTask_id' => $compareTask->id,
            ]);

            return null;

        }

        return $reduceResult->refresh();

    }



    protected function findJob(Tasks $task1, Tasks $task2)
    {

        return app(TaskService::class)->findJob($task1, $task2);

    }


    protected function findApplication(Tasks $task1, Tasks $task2)
    {

        return app(TaskService::class)->findApplication($task1, $task2);

    }


    protected function findSplitTask(Tasks $task1, Tasks $task2)
    {

        if ($task1->shouldSplit()) return $task1;

        if ($task2->shouldSplit()) return $task2;

        return null;

    }

    protected function findReduceTask(Tasks $task1, Tasks $task2)
    {

        if ($task1->shouldReduce()) return $task1;

        if ($task2->shouldReduce()) return $task2;

        return null;

    }


    protected function findNoneTask(Tasks $task1, Tasks $task2)
    {

        if ($task1->shouldNone()) return $task1;

        if ($task2->shouldNone()) return $task2;
        return null;

    }


    protected function isValid(Tasks $task1, Tasks $task2)
    {

        $taskService = app(TaskService::class);
        $matchService = app(MatchService::class);


        $isValid = $matchService->isMatching($task1, $task2)
            && $taskService->taskIsEditable($task1)
            && $taskService->taskIsEditable($task2);


        if (!$isValid) {

            Log::warning("HandleSplitTasks: Match is not valid", [
                'task1_id' => $task1->id,
                'task2_id' => $task2->id,
            ]);

        }

        return $isValid;

    }



    private function getCompareTask(Tasks $mytask)
    {

        foreach ($this->tasks as $task) {

            if ($mytask->id !== $task->id) {

                return $task;

            }

        }

        return null;

    }


    private function handleSplitOrReduceWithCode(string $code, Tasks $task1, Tasks $task2)
    {

        $job = $this->findJob($task1, $task2);

        $application = $this->findApplication($task1, $task2);


        if (!$job || !$application) {

            Log::warning("HandleSplitTasks: Kunne ikke finde job eller application", [
                'task1_id' => $task1->id,
                'task2_id' => $task2->id,
                'job_found' => (bool) $job,
                'application_found' => (bool) $application,
            ]);

            throw new \Exception('Job or application not found');

        }

        switch ($code) {

            case "NN":

                return $this->taskNoneNone($job, $application);

            case "NS":

                return $this->taskNoneSplit($job, $application);

            case "NR":

                return $this->taskNoneReduce($job, $application);

            case "RR":

                return $this->taskReduceReduce($job, $application);

            case "SS":

                return $this->taskSplitSplit($job, $application);

            case "RS":

                return $this->taskSplitReduce($job, $application);
                
            default:

                Log::warning("HandleSplitTasks: Ukendt split-kode i switch", [
                    'code' => $code,
                    'task1_id' => $task1->id,
                    'task2_id' => $task2->id,
                ]);

                return null;

        }

    }

    private function hireTask($job, $compareTask)
    {

        return app(HireService::class)->hireTask($job, $compareTask);

    }

    private function taskNoneNone(Tasks $job, Tasks $application)
    {

        return [
            "hire" => $this->hireTask($job, $application),
        ];

    }

    private function taskNoneSplit(Tasks $job, Tasks $application)
    {

        $split = $this->findSplitTask($job, $application);  
        
        $compare = $this->getCompareTask($split);

        $splitResult = $this->split($split, $compare);
        
        $allocation = $splitResult["allocation"];


        if($split->isJob()){

            $hireTask = $this->hireTask($allocation, $compare);

        } else {

            $hireTask = $this->hireTask($job, $allocation);

        }


        return [
            "split" => $splitResult,
            "hire" => $hireTask
        ];

    }

    private function taskNoneReduce(Tasks $job, Tasks $application)
    {

        $reduce = $this->findReduceTask($job, $application);

        $compare = $this->getCompareTask($reduce);

        $justBothDates = false;

        $reduceTask = $this->reduce($reduce, $compare, $justBothDates);
        
        $hireTask = $this->hireTask($job, $application);


        return [
            "reduce" => $reduceTask ,
            "hire" => $hireTask,
        ];

    }

    private function taskReduceReduce(Tasks $job, Tasks $application)
    {

        $justBothDates = false;
        
        $reduces["job"] = $this->reduce($job, $application, $justBothDates);
            
        $reduces["application"]  = $this->reduce($application, $job, $justBothDates);

        $hireTask = $this->hireTask($job, $application);


        return [
            "reduces" => $reduces,
            "hire" => $hireTask
        ];

    }

 

    private function taskSplitReduce(Tasks $job, Tasks $application)
    {

        $splitTask = $this->findSplitTask($job, $application);
        $reduceTask = $this->findReduceTask($job, $application);


        $splitTasks = $this->split($splitTask, $reduceTask);

        $allocation = $splitTasks["allocation"];

        $justBothDates = true;

        $newReduceTask = $this->reduce($reduceTask, $allocation, $justBothDates);


        $hireTask = $allocation->isJob()
            ? $this->hireTask($allocation, $newReduceTask)
            : $this->hireTask($newReduceTask, $allocation);


        return [
            "split" => $splitTasks,
            "allocation" => $allocation,
            "reduce" => $newReduceTask,
            "hire" => $hireTask
        ];

    }



    private function taskSplitSplit(Tasks $job, Tasks $application)
    {


        $jobSplit = $this->split($job, $application);

        $applicationSplit = $this->split($application, $job);

        
      


        $allocation = [
            "job" => $jobSplit["allocation"],
            "application" => $applicationSplit["allocation"]
        ];


  
        $hireTask = $this->hireTask($allocation["job"], $allocation["application"]);

  
        return [
            "split" => [
                "job" => $jobSplit,
                "application" => $applicationSplit,
            ],
            "hire" => $hireTask
        ];

    }

}
