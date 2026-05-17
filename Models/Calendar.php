<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Calendar\Models\Calendar as origCalendar;
use Modules\Tasks\Models\Tasks;


class Calendar extends origCalendar
{

    protected $appends = [
        'job_id', 
        'application_id'
    ];


    protected $fillable = [
        'type',
        'title',
        'slug',
        'description',
        'color',
        'active',
        'user_id',
        'data',
        'link',
        'task_id'
    ];

    

    public function getJobIdAttribute(): ?int
    {
        return $this->data['job_id'] ?? null;
    }


    public function getApplicationIdAttribute(): ?int
    {
        return $this->data['application_id'] ?? null;
    }



 
    public function task()
    {
        
        return $this->hasOne(Tasks::class, 'id',"task_id");

    }

}
