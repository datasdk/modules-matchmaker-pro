<?php

namespace Modules\Tasks\Console\Commands;

use Illuminate\Console\Command;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\TaskStatusCodeService;
use Modules\Tasks\Jobs\ProcessTasks;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class UpdateTaskStatus extends Command
{


    protected $signature = 'tasks:update-status';

    protected $description = 'Updates task status based on availability dates';


    public function handle()
    {


        $taskStatusCodeService = new TaskStatusCodeService();

        $now = Carbon::now();

        $activeStatusCodes = ["live", "ongoing", "pending"];


        $tasks = Tasks::whereIn('status', $activeStatusCodes)
        ->whereHas('available', function ($query) {

            $query->whereDate('from', '<', Carbon::today());

        })->get();


        $updatedCount = 0;



        foreach ($tasks as $task) {

            // Dispatch job for each task
            ProcessTasks::dispatch($task)->onConnection('sync');

            $updatedCount++;
            
        }



        $message = "Dispatched {$updatedCount} tasks  {$now->toDateTimeString()}.";

        // Console output
        $this->info($message);


     


    }

}
