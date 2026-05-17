<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;


class TaskMatchRatingTest extends TestExtend
{

    
    use RefreshDatabase;
    

    protected function setUp(): void
    {

        parent::setUp();

        // Opret et match og gem job + application
        $result = $this->makeMatch(
            ['amount' => 2],
            ['amount' => 2]
        );
    

        $this->assertNotNull($result, 'Expected match to be created in setUp');


    }


    public function test_is_rating_created()
    {

        foreach ([$this->job, $this->application] as $task) {

            $this->assertNotNull($task->scheduledRatings, "Ratings relation should not be null for task ID {$task->id}");

            $this->assertGreaterThan(0, $task->scheduledRatings()->count(), "No ratings were created for task ID {$task->id}");

        }
    }
}
