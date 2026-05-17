<?php

namespace Modules\Tasks\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Tasks\Models\TaskRatings;
use Modules\Tasks\Models\TaskRatingsAvg;
use Illuminate\Database\Eloquent\Relations\MorphOne;


trait CanBeRated
{
    /**
     * Definer relationen til TaskRating-modellen
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(TaskRatings::class, 'target');
    }

/*
     public function avgRating(): MorphMany
    {
        return $this->ratings()->avg("stars");
    }
*/
       /**
     * Polymorf relation til TaskRatingsAvg med en default værdi.
     */
    public function avg(): MorphOne
    {
        return $this->morphOne(TaskRatingsAvg::class, 'subject')->withDefault([
            "rating" => 0
        ]);
    }

    /**
     * Hent ratings givet af den autentificerede bruger
     */
    public function ratingsByMe()
    {
        $user = request()->user();
        if (!$user) {
            return null;
        }

        return $this->ratings()->where('rater_id', $user->id)
            ->where('rater_type', get_class($user))
            ->get();
    }


    public function getAverageRatingAttribute()
    {
        return $this->avg->rating;
    }

    /**
     * Hent gennemsnittet af alle ratings på tværs af typer
     */
    public function getAverageRatingAllTypesAttribute()
    {
        return round(TaskRatings::where('target_id', $this->id)
            ->where('target_type', get_class($this))
            ->avg('stars') ?? 0, 1);
    }

    /**
     * Tjek om den autentificerede bruger har rated denne model
     */
    public function getIsRatedByMeAttribute()
    {
        $user = request()->user();
        if (!$user) {
            return false;
        }

        return $this->ratings()->where('rater_id', $user->id)
            ->where('rater_type', get_class($user))
            ->exists();
    }


    
    public function getIsFavoritedByMeAttribute()
    {
        return $this->isFavoritedBy(auth()->user());
    }
  
    

  

    /**
     * Giv en rating til denne model
     */
    public function rate(Model $rater, int $stars)
    {
        if ($stars < 1 || $stars > 5) {
            throw new \InvalidArgumentException("Rating must be between 1 and 5 stars.");
        }

        return $this->ratings()->updateOrCreate([
            'rater_id' => $rater->id,
            'rater_type' => get_class($rater),
        ], [
            'stars' => $stars,
        ]);
    }

    /**
     * Opdater en eksisterende rating
     */
    public function updateRating(Model $rater, int $stars)
    {
        return $this->rate($rater, $stars);
    }

    /**
     * Slet en rating
     */
    public function deleteRating(Model $rater)
    {
        return $this->ratings()->where([
            'rater_id' => $rater->id,
            'rater_type' => get_class($rater),
        ])->delete();
    }
}
