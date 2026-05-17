<?php

namespace Modules\Tasks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Tasks\Models\Tasks;

class TaskHired
{
    use Dispatchable;


    public $job;

    public $application;
    
    public $params;


    public function __construct(Tasks $job, Tasks $application, array $params)
    {
   
        $this->job = $job;

        $this->application = $application;

        $this->params = $params;

    }
}