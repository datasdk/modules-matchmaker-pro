<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\Hires;
use Modules\Media\Services\PDFGenerator;
use Modules\Media\Services\MediaLibraryService;
use Modules\Email\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Modules\Tasks\Services\TaskStatusCodeService as Status;


class HireService
{

    public function hireTask(Tasks $job, Tasks $application)
    {

        if ($job->id == $application->id) {
            return null;
        }

        if ($job->type != "job" || $application->type != "application") {
            return null;
        }

        $hasHired = $job->hasHired($application);

        $with = ['user', 'user.contact', 'address', 'available', 'categories', 'company'];

        $job->load($with);

        $application->load($with);


   
        $hired = Hires::firstOrCreate([
            'task_id' => $job->id,
            'hired_task_id' => $application->id
        ]);

        
        $this->updateStatus($job);
         $this->updateStatus($application);


        $params = [
            'task' => $job->translate(),
            'applicant' => $application->translate(),
        ];


        // Dispatch the event
        event(new \Modules\Tasks\Events\TaskHired($job, $application, $params));


        


        return [
            "application" => $application->load("available"),
            "job" => $job->load("available")
        ];

    }


    private function updateStatus(Tasks $task){


        $newStatus = $task->available->from->greaterThan(now()) ? Status::PENDING : Status::ONGOING;
         
        if ($task->status !== $newStatus) {

            $task->status = $newStatus;

            $task->save();

        }


        return $task->refresh();
    }

}