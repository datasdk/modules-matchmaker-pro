<?php

namespace Modules\Tasks\Observers;

use Modules\Tasks\Models\TaskRatings;
use Modules\Tasks\Models\TaskRatingsAvg;
use Modules\Tasks\Models\TaskRatingsScheduled;


class TaskRatingsObserver
{
    /**
     * Opdater gennemsnitsrating når en rating oprettes eller opdateres.
     */
    public function saved(TaskRatings $rating)
    {

        $this->updateAverageRating($rating);

        $this->deleteScheduledRating($rating);

    }


    /**
     * Opdater gennemsnitsrating når en rating slettes.
     */
    public function deleted(TaskRatings $rating)
    {

        $this->updateAverageRating($rating);

    
    }

    /**
     * Hjælpefunktion til at opdatere gennemsnit.
     */
    protected function updateAverageRating(TaskRatings $rating)
    {

        $target_type = $rating->target_type;
        $target_id = $rating->target_id;

        $averageRating = TaskRatings::where('target_type', $target_type)
                                    ->where('target_id', $target_id)
                                    ->avg('stars');


        // Hvis ingen ratings findes, kan vi evt. slette gennemsnittet
        if ($averageRating === null) {
            TaskRatingsAvg::where('subject_type', $target_type)
                          ->where('subject_id', $target_id)
                          ->delete();
            return;
        }


        TaskRatingsAvg::updateOrCreate(
            [
                'subject_type' => $target_type,
                'subject_id'   => $target_id,
            ],
            [
                'rating' => round($averageRating, 1),
            ]
        );

    }

    
    protected function deleteScheduledRating(TaskRatings $rating){
        
        return TaskRatingsScheduled::query()
        ->where('match_id', $rating->match_id)
        ->where('user_id', $rating->user_id)
        ->delete();

    }
     
}
