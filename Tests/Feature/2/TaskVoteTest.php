<?php

namespace Modules\Tasks\Tests\Feature;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\Tasks;
use App\Models\User;

use Illuminate\Support\Facades\Artisan;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;


class TaskVoteTest extends TestExtend
{
    use RefreshDatabase;
  


    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call("db:seed", ["--class" => CountrySeeder::class]);
        Artisan::call("module:seed tasks");

        $this->user = $this->createUser();
        $this->user2 = $this->createUser();


        $this->task = $this->createTask([
            "user_id" => $this->user->id,
            "amount" => 5,
        ])->set_available([
            "from"  => "2025-01-01",
            "to"    => "2025-02-01"
        ]);


        $this->task2 = $this->createTask([
            "user_id" => $this->user2->id,
            "amount" => 5
        ])->set_available([
            "from"  => "2025-01-01",
            "to"    => "2025-02-01"
        ]);


    }


    public function test_can_vote_for_task(): void
    {

        $params = [
            "task_id" => $this->task->id,
            "like_task_id" => $this->task2->id,
            "vote" => 1
        ];

        $response = $this->actingAs($this->user)->postJson(route('api.tasks.vote.store'), $params);

        $response->assertSuccessful();
    
    }

    public function test_can_reject_task(): void
    {

        $params = [
            "task_id" => $this->task->id,
            "like_task_id" => $this->task2->id,
            "vote" => 0
        ];

        $response = $this->actingAs($this->user)->postJson(route('api.tasks.vote.store'), $params);

        $response->assertSuccessful();
    
    }



    public function test_can_reset_vote_for_task(): void
    {

        $response = $this->actingAs($this->user)->deleteJson(route('api.tasks.vote.destroy', $this->task->id));

        $response->assertSuccessful();
    
    }


  
}
