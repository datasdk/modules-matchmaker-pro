<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Tasks\Contracts\RatingInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskRatings extends Model implements RatingInterface
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'rater_id', 
        'rater_type', 
        'target_id', 
        'target_type',
        'match_id', 
        'task_id',
        'user_id',
        'stars', 
        'type',
    ];
    
    /**
     * Polymorphic relation to the model that gave the rating.
     */
    public function rater(): MorphTo
    {
   
        return $this->morphTo();
    }

    /**
     * Polymorphic relation to the model that receives the rating.
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Get ratings given by this rating's rater.
     */
    public function givenRatings()
    {
        return $this->morphMany(TaskRatings::class, 'rater');
    }

    /**
     * Get ratings received by this rating's target.
     */
    public function receivedRatings()
    {
        return $this->morphMany(TaskRatings::class, 'target');
    }

  

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'rater_id');
    }

    
    public function taskForRate()
    {
        return $this->belongsTo(Tasks::class, 'target_id');
    }


  
}
