<?php

namespace Modules\Tasks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Modules\Tasks\Models\Tasks;
use Carbon\Carbon;


class ProcessTasks implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $task;


    /**
     * Create a new job instance.
     *
     * @param Tasks $task
     */
    public function __construct(Tasks $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $task = $this->task;

        $now = Carbon::now();

        $status = $task->status;


        $from = optional(optional($task->available)->from)->startOfDay();

        $to   = optional(optional($task->available)->to)->endOfDay();

        $hireAmount = $task->hires->sum('amount');



        // Hvis ingen fra/til er sat
        if (!$from || !$to) {

            $status = $task->status;

        }

        elseif ($task->status === 'splitted') {

            $status = 'splitted';

        }

        elseif ($to && $to->lessThan($now)) {

            
            if ($task->amount == $hireAmount) {
                
                $status = 'closed';

            } else {

                $status = 'hold';

            }
            

        }

        elseif (in_array($task->status, ['live', 'pending'])) {


            if ($task->amount == $hireAmount) {


                if ($from && $from->lessThan($now)) {

                    $status = 'pending';

                } elseif ($from && $from->greaterThanOrEqualTo($now)) {

                    $status = 'ongoing';

                }


            } else {


                if ($from && $from->lessThan($now)) {

                    $status = 'hold';

                }


            }


        }


        // Opdater kun hvis status ændres
        if ($status !== $task->status) {

            $task->status = $status;

            $task->saveQuietly();
            
        }

    }

     /**
     * Job middleware to prevent overlapping.
     *
     * @return array
     */
    public function middleware(): array
    {

        return [new WithoutOverlapping($this->task->id)];

    }
    
}
