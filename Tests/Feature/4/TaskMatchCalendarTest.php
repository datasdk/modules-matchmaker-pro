<?php

namespace Modules\Tasks\Tests\Feature;

use Tests\TestCase;
use Modules\Tasks\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskMatch;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Database\Seeders\TasksDatabaseSeeder;
use Illuminate\Support\Facades\Artisan;
use Modules\Email\Models\Email;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;


class TaskMatchCalendarTest extends TestExtend
{

    use RefreshDatabase;
 

    protected function setUp(): void
    {
        parent::setUp();


        Artisan::call("db:seed",[
            "--class" => CountrySeeder::class
        ]);

        Artisan::call("module:seed tasks");

        $this->makeMatch();

    }



    public function test_is_calendar_created()
    {

        $tasks = [$this->job, $this->application]; // tilføj flere hvis nødvendigt

        foreach ($tasks as $task) {
            $this->assertNotNull($task->calendar, "Calendar relation should not be null for task ID {$task->id}");
            $this->assertGreaterThan(0, $task->calendar()->count(), "No calendar entries were created for task ID {$task->id}");
        }
    }






}
