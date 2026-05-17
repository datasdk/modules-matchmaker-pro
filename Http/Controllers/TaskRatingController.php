<?php

namespace Modules\Tasks\Http\Controllers;

use Modules\Tasks\Models\TaskRatings as Ratings;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\RatingService;
use Modules\Tasks\Http\Requests\RatingRequest;
use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;

class TaskRatingController extends OrionBaseController
{
    protected $model = Ratings::class;
    protected $request = RatingRequest::class;

    /**
     * Display a listing of the ratings.
     */
    public function index(Request $req)
    {
        return view('tasks::ratings.index');
    }

    /**
     * Show the form for creating a new rating.
     */
    public function create(Request $req)
    {
        $ratingTypes = $this->getRatingTypes();
        return view('tasks::ratings.create', compact('ratingTypes'));
    }

    /**
     * Store a newly created rating.
     */
    public function store(Request $req)
    {

        try {


            $user_id = $req->user()->id;

            $task = Tasks::findOrFail($req->task_id);

            $taskForRate = Tasks::findOrFail($req->task_for_rate_id);

            $rating = $req->ratings ?? $req->rating;

            $type = $req->type ?? 'general';


            $result = (new RatingService())->rate($task, $taskForRate, $rating, $user_id, $type);


            if ($result) {

                return redirect()->route('tasks.rating.index')->with('success', 'Rating created successfully');

            }


            return back()->withErrors(['message' => 'Kunne ikke oprette rating. Task indeholder ikke rater-model eller target-model.']);


        } catch (\Exception $e) {

            return back()->withErrors(['message' => $e->getMessage()]);

        }
    }

    /**
     * Display the specified rating.
     */
    public function show(Request $req, ...$args)
    {

        $id = $args[0] ?? null;


        $rating = Ratings::findOrFail($id);
        return view('tasks::ratings.show', compact('rating'));

    }


    /**
     * Show the form for editing a rating.
     */
    public function edit(Request $req, ...$args)
    {

        $id = $args[0] ?? null;

        $rating = Ratings::findOrFail($id);

        $ratingTypes = $this->getRatingTypes();

        return view('tasks::ratings.edit', compact('rating', 'ratingTypes'));

    }

    /**
     * Update the specified rating.
     */
    public function update(Request $req, ...$args)
    {

        $id = $args[0] ?? null;

        try {

            $user_id = $req->user()->id;

            $ratingRecord = Ratings::findOrFail($id);

            $task = Tasks::findOrFail($req->task_id);

            $taskForRate = Tasks::findOrFail($req->task_for_rate_id);

            $rating = $req->ratings ?? $req->rating;

            $type = $req->type ?? 'general';


            $result = (new RatingService())->updateRate($ratingRecord, $task, $taskForRate, $rating, $user_id, $type);


            if ($result) {

                return redirect()->route('tasks.rating.index')->with('success', 'Rating updated successfully');

            }


            return back()->withErrors(['message' => 'Kunne ikke opdatere rating. Task indeholder ikke rater-model eller target-model.']);
        
        } catch (\Exception $e) {

            return back()->withErrors(['message' => $e->getMessage()]);

        }

    }



    /**
     * Remove the specified rating.
     */
    public function destroy(Request $req, ...$args)
    {
        
        $id = $args[0] ?? null;

        try {
            $rating = Ratings::findOrFail($id);
            (new RatingService())->delete($rating);
            return redirect()->route('tasks.rating.index')->with('success', 'Rating deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Return the available rating types.
     */
    private function getRatingTypes(): array
    {
        return [
            'general' => 'Generel',
            'leveringdygtig' => 'Leveringdygtig',
            'kvalitet' => 'Kvalitet',
            'service' => 'Service',
            'tid' => 'Tid',
            'kommunikation' => 'Kommunikation',
            // Tilføj flere kvaliteter efter behov
        ];
    }
}
