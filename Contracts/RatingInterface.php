<?php

namespace Modules\Tasks\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface RatingInterface
{
    /**
     * Polymorphic relation to the model that gave the rating.
     */
    public function rater(): MorphTo;

    /**
     * Polymorphic relation to the model that receives the rating.
     */
    public function target(): MorphTo;

    /**
     * Get ratings given by this rating's rater.
     */
    public function givenRatings();

    /**
     * Get ratings received by this rating's target.
     */
    public function receivedRatings();



    public function task();


    public function taskForRate();

    
}
