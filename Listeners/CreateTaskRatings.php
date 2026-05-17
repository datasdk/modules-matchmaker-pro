<?php
namespace Modules\Tasks\Listeners;

use Modules\Tasks\Services\RatingService;

class CreateTaskRatings
{

    public function handle($event)
    {

        $ratingsService = app(RatingService::class);

        $rating_job = $ratingsService->setScheduledRating($event->job, $event->application);

        $rating_application = $ratingsService->setScheduledRating($event->application, $event->job);


        return [
            "job" => $rating_job,
            "application" => $rating_application
        ];

    }
    
}