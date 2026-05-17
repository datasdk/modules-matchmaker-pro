<?php

namespace Modules\Tasks\Listeners;

use Modules\Tasks\Events\MatchCreated;
use Modules\Firebase\Services\NotificationService as FirebaseService;
use Modules\Email\Services\EmailService;
use Modules\Tasks\Models\Tasks;
use App\Models\User;


class SendMatchCreatedNotification
{

    public function handle(MatchCreated $event)
    {

        $job = $event->job;

        $application = $event->application;


        return [
            $this->notify($job, "job-has-matched-applicant"),
            $this->notify($application, "applicant-has-matched-job")
        ];      

    }



    protected function notify(Tasks $task,string $draft)
    {

        $user = $task->user;

        if ($user) {

            return (new FirebaseService())->send([
                'user_id' => $user->id,
                'draft_id' => $draft,
                'params' => $task->toArray()
            ]);

        }


    }

}
