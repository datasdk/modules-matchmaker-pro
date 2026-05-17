<?php

namespace Modules\Tasks\Http\Controllers\Api;

use Modules\Tasks\Models\TaskRatingsScheduled;
use Modules\Tasks\Services\RatingService; // hvis relevant, ellers fjern
use Orion\Http\Requests\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Http\Requests\TaskRatingsScheduledRequest; // opret denne request for validering

class TaskRatingSchedulesController extends OrionBaseController
{

    protected $model = TaskRatingsScheduled::class;

    protected $request = TaskRatingsScheduledRequest::class;

    protected $includes = [
        'task',
        'user',
        'taskForRate',
        "taskForRate.company",
        "taskForRate.address",
        "taskForRate.categories",
        "taskForRate.user.contact",
        "taskForRate.available",
        "image",
        "task"
    ];

 
    public function store(Request $req)
    {

        $data = $req->validated();

        $rating = TaskRatingsScheduled::create($data);

        return response()->json($rating, 201);

    }


    public function update(Request $req, ...$args)
    {

        try {
            
            $id = $args[0];

            $ratingInstance = TaskRatingsScheduled::with(['task', 'taskForRate'])->findOrFail($id);

            if (!$ratingInstance->task || !$ratingInstance->taskForRate) {
                return response()->json([
                    'message' => 'Rating must have an associated task and taskForRate.'
                ], 422);
            }

            $data = $req->validated();

            $ratingInstance->update($data);

            // Hvis du har en RatingService som skal opdatere noget logik, kan du kalde den her
            // $result = (new RatingService())->someUpdateMethod(...);

            return response()->json($ratingInstance);

        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 500);

        }

    }


    public function destroy(Request $req, ...$args)
    {
        try {
                
            $id = $args[0];

            $rating = TaskRatingsScheduled::findOrFail($id);

            $rating->delete();

            return response()->noContent();

        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);

        }
        
    }

}
