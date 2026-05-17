<?php

namespace Modules\Tasks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Tasks\Models\Tasks;


class TaskGotInteraction
{

    use Dispatchable, SerializesModels;

    public Tasks $task;

    /**
     * Create a new event instance.
     *
     * @param  \Modules\Tasks\Models\Tasks  $task
     */

    public function __construct(Tasks $mytask,Tasks $task)
    {

        $this->mytask = $mytask; // liked task

        $this->task = $task; // other task

    }

}
