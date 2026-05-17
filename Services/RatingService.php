<?php

namespace Modules\Tasks\Services;

use Illuminate\Support\Facades\Log;
use Modules\Tasks\Models\Matches;
use Modules\Calendar\Models\Calendar;
use Modules\Companies\Models\Companies;
use Modules\Reviews\Models\Interaction;
use Modules\Firebase\Services\NotificationService;
use App\Models\User;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskRatingsScheduled;
use Modules\Tasks\Services\MatchService;
use Modules\Reviews\Services\RatingService as ReviewRatingService;
use Modules\Tasks\Models\TaskRatings;
use Modules\Tasks\Models\TaskRatingsAvg;
use Illuminate\Database\Eloquent\Model;


class RatingService
{



    public function setScheduledRating(Tasks $task, Tasks $taskForRate)
    {


        $matchService = new MatchService();

        $match_id = $matchService->getMatchId($task, $taskForRate);

        $uid = $this->generateUid($task,$taskForRate);


        // Opret eller hent eksisterende rating
        $event = TaskRatingsScheduled::firstOrCreate(
            [
                'uid'   => $uid,
                'task_id' => $task->id,
                'task_for_rate_id' => $taskForRate->id,
                'match_id' => $match_id,
            ],
            [
                'user_id' => $task->user_id, // Sørger for at user_id altid bliver sat
            ]
        );

        
      

        $from = $task->available->to;

       
        


        // Sæt available-perioden og gem
        $event->set_available([
            "from" => $from, 
            "to" => null
        ]);

        $event->save();


        return $event->load("available")->refresh();

    }



    // task rates ..

    public function rate(Tasks $task, Tasks $taskForRate, int|array $rating, $user_id)
    {

        if ($task->id === $taskForRate->id) {
           throw new \Exception('Du kan ikke vurdere dig selv.');
        }

        $matchService = new MatchService();

        $match_id = $matchService->getMatchId($task, $taskForRate);


        $rater = $this->getRatingModel($task);

        if (!$rater) {
            return null;
        }

        $target = $this->getRatingModel($taskForRate);

        if (!$target) {
            return null;
        }


        $results = [];

        if (is_array($rating)) {

            // Flere ratings - valider hver entry
            foreach ($rating as $ratingValue) {


                $stars = $ratingValue["stars"];

                $category = $ratingValue["category"];


                $ratingResult = $this->updateOrCreate($rater, $target, $stars, $category, $user_id,$match_id);


                if (is_null($ratingResult)) {

                    \Log::warning("Rating updateOrCreate returned null", [
                        'rater_id' => $rater->id ?? null,
                        'target_id' => $target->id ?? null,
                        'rating_type' => $category,
                        'user_id' => $user_id,
                        'rating_value' => $ratingValue,
                    ]);

                }


                $results[$category] = $ratingResult;


            }


        } else {


            $category = "generalt";

            $ratingResult = $this->updateOrCreate($rater, $target, $rating, $category, $user_id,$match_id);


            if (is_null($ratingResult)) {
                \Log::warning("Rating updateOrCreate returned null", [
                    'rater_id' => $rater->id ?? null,
                    'target_id' => $target->id ?? null,
                    'rating_type' => $category,
                    'user_id' => $user_id,
                    'rating_value' => $rating,
                ]);
            }


            $results[$category] = $ratingResult;


        }


        return [
            "rater" => $rater->refresh(),
            "target" => $target->refresh(),
            "results" => $results,
        ];

    }



    private function getRatingModel(Tasks $task)
    {


        if ($task->company_id && $task->company) {

            $model = $task->company;


        } else if($task->user_id){

            $model = $task->user;

        } else {


            \Log::warning("getRatingModel returnerede null: Mangler company_id eller company", [
                'task_id' => $task->id,
                'company_id' => $task->company_id ?? null,
                'company' => $task->company ?? null,
            ]);


            return null;

        }


        // Check om $model findes

        if (!$model) {
            \Log::warning("getRatingModel fandt ikke model for task", [
                'task_id' => $task->id,
                'company_id' => $task->company_id ?? null,
                'user_id' => $task->user_id ?? null,
            ]);

            return null;
        }


        return $model;

    }


    private function updateOrCreate(Model $rater, Model $target, int $stars, $category = "general", $user_id,$match_id)
    {

        if ($rater->id === $target->id) {
           throw new \Exception('Du kan ikke vurdere dig selv.');
        }



        $rater_type = $rater->getMorphClass();

        $target_type = $target->getMorphClass();
        

        $uid = $this->generateUid($rater,$target);
        
        
        // Opdater eller opret rating
        $task = TaskRatings::updateOrCreate([
            'uid'        => $uid,
            'match_id'   => $match_id,
            'rater_type' => $rater_type, 
            'rater_id'   => $rater->id,
            'target_type'=> $target_type,
            'target_id'  => $target->id,
            'user_id'    => $user_id,
            'type'       => $category,
            'stars'      => $stars,
            'task_id'    => $rater->id
        ]);
  
      

        return $task;

    }
    

    private function generateUid(Model $rater, Model $target){
        
        $rater_type = $rater->getMorphClass();
            
        $target_type = $target->getMorphClass();

        return sha1($rater_type."-".$target->id."-".$target_type."-".$target->id);

    }


    public function delete(TaskRatings $rating): bool
    {
        try {
            return (bool) $rating->delete();
        } catch (\Exception $e) {
            \Log::error('Fejl under sletning af rating:', [
                'rating_id' => $rating->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
}
