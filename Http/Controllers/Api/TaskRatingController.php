<?php

namespace Modules\Tasks\Http\Controllers\Api;

use Modules\Tasks\Services\RatingService;
use Modules\Tasks\Models\TaskRatings as Ratings;
use Modules\Tasks\Models\Tasks;
use Orion\Http\Requests\Request;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Http\Requests\RatingRequest;

class TaskRatingController extends OrionBaseController
{

    protected $model = Ratings::class;

    protected $request = RatingRequest::class;


    protected $includes = [
        "task",
        "taskForRate",
        "taskForRate.categories",
        "taskForRate.company",
        "taskForRate.address",
        "taskForRate.contact",
        "taskForRate.user",
        "taskForRate.user.contact",
        "taskForRate.available",
        "user"
    ];


    public function store(Request $req)
    {

        try {

            $user_id = $req->user()->id;

            $task = Tasks::findOrFail($req->task_id);
            $taskForRate = Tasks::findOrFail($req->task_for_rate_id);

            $rating = $req->ratings ?? $req->rating;

            $result = (new RatingService())->rate($task, $taskForRate, $rating, $user_id);

            if ($result) {
                return response()->json($result, 201);
            }


            return response()->json([
                'message' => 'Could not rate. Tasks does not contain rater-model or target-model.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

/*
DER ER FEJL I DENNE
    public function update(Request $req, $id)
    {
        try {

            $user_id = $req->user()->id;

            $ratingRecord = Ratings::findOrFail($id);

            $task = Tasks::findOrFail($req->task_id);
            $taskForRate = Tasks::findOrFail($req->task_for_rate_id);

            $rating = $req->ratings ?? $req->rating;

            $result = (new RatingService())->updateRate($ratingRecord, $task, $taskForRate, $rating, $user_id);


            if ($result) {
                return response()->json($result, 200);
            }


            return response()->json([
                'message' => 'Could not update rating. Tasks does not contain rater-model or target-model.'
            ], 404);


        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 500);

        }


    }
*/

    public function destroy(Request $req, ...$args)
    {
        
        $id = $args[0];

        $ratingService = new RatingService();

        $rating = Ratings::findOrFail($id);


        try {

            $data = $req->validated();

            $deleted = $ratingService->delete($rating);

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }

    }

}
