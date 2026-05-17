<?php

namespace Modules\Tasks\Observers;

use Modules\Tasks\Models\Tasks;
use Carbon\Carbon;

class TasksObserver
{

    /**
     * Håndter "creating"-eventet for Tasks-modellen.
     */
    public function creating(Tasks $task): void
    {

        // Sæt case_number automatisk hvis det ikke allerede findes
        if (empty($task->case_number)) {

            $task->case_number = $this->generateCaseNumber();

        }

        // Default status
        if (empty($task->status)) {

            $task->status = 'draft';

        }


        $this->incrementCategoryUsage($task);

    }


    /**
     * Håndter "updating"-eventet for Tasks-modellen.
     */
    public function updating(Tasks $task): void
    {

        if (empty($task->case_number)) {

            $task->case_number = $this->generateCaseNumber();

        }

        $this->incrementCategoryUsage($task);

    }


    /**
     * Opdaterer usage-counter for tilknyttede kategorier.
     */
    private function incrementCategoryUsage(Tasks $task): void
    {

        if (!$task->categories || !$task->hasCounter('usage')) {

            return;

        }


        $task->categories->each(function ($category) {

            $category?->incrementCounter('usage');

        });

    }


    private function generateCaseNumber(): string
    {
        $prefix = "WB"; // eller null

        $nextNumber = (Tasks::max('id') ?? 0) + 1;

        
        return ($prefix ? $prefix . '-' : '') . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        
    }


}
