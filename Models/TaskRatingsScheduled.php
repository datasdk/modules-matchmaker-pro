<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use DataSDK\Available\Traits\Available;
use DataSDK\Tools\Traits\Language;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TaskRatingsScheduled extends Model
{

    use Available,Language;
   // use SoftDeletes;
    use HasFactory;
    

    protected $fillable = [
        'uid', 
        'match_id',
        'user_id', 
        'task_id', 
        'task_for_rate_id'
    ];

    
    protected $table = "tasks_ratings_scheduled";

    protected $translatable = [];
    

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function taskForRate()
    {
        return $this->belongsTo(Tasks::class, 'task_for_rate_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
