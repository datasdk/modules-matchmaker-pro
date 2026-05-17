<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\TaskStatusCodeService as Status;


class TaskReductionService
{

    public function reduce(Tasks $task, Tasks $compareTask, bool $justBothDates): Tasks
    {


        if ($justBothDates) {

            $this->adjustBothEndDates($task, $compareTask);

        } else {

            $this->expandEndDate($task, $compareTask);

        }


        $this->reduceAmount($task, $compareTask);


     

        return $task->refresh();

    }


    private function adjustBothEndDates(Tasks $task, Tasks $compareTask): void
    {

        $task->set_available([
            'from' => $compareTask->available->from,
            'to' => $compareTask->available->to,
        ]);

    }


    private function expandEndDate(Tasks $task, Tasks $compareTask): void
    {

        if ($compareTask->available->to && $task->available->to->gt($compareTask->available->to)) {

            $task->set_available_to($compareTask->available->to);

        }

    }


    private function reduceAmount(Tasks $task, Tasks $compareTask): void
    {

        if ($task->amount > $compareTask->amount) {

            $task->amount = $compareTask->amount;

            $task->save();

        }

    }


 


}
