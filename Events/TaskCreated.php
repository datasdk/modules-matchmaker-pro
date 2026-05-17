<?php

namespace Modules\Tasks\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Tasks\Models\Tasks;

class TaskCreated
{

    use SerializesModels;

    public Tasks $task;

    
    public function __construct(Tasks $task)
    {

        $this->task = $task;

    }

}
