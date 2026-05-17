<?php

namespace Modules\Tasks\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\TaskRatingsScheduled;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Illuminate\Support\Facades\Artisan;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Tasks\Services\MatchService;



class TaskRatingsScheduledTest extends TestExtend
{
    
    use RefreshDatabase;

    public $match;
    public $matchId;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed needed data
        Artisan::call("db:seed", ["--class" => CountrySeeder::class]);

        // Lav et match med default parametre, så $this->job, $this->application og $this->user mv. er sat
        $this->match = $this->makeMatch(
            ['amount' => 2],
            ['amount' => 2]
        );

        $this->matchId = app(MatchService::class)->getMatchId($this->job, $this->application);

        // Efter makeMatch er kørt, har vi:
        // $this->job, $this->application, $this->user, $this->user2 sat til brug i tests
    }


    /** @test */
    public function user_can_list_ratings_schedules()
    {
        
    
        TaskRatingsScheduled::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'task_id' => $this->job->id,
            'task_for_rate_id' => $this->application->id,
            'match_id' => $this->matchId ,
        ]);


        $response = $this->actingAs($this->user)->getJson(route('api.tasks.ratings_schedules.index'));

        $response->assertOk();

    }


    /** @test */
    public function user_can_create_task_rating_scheduled()
    {

        
        $payload = [
            'user_id' => $this->user->id,
            'task_id' => $this->job->id,
            'task_for_rate_id' => $this->application->id,
            'match_id' => $this->matchId ,
        ];

        $response = $this->actingAs($this->user)->postJson(route('api.tasks.ratings_schedules.store'), $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'user_id' => $this->user->id,
                'task_id' => $this->job->id,
                'task_for_rate_id' => $this->application->id,
                'match_id' => $this->matchId ,
            ]);

        $this->assertDatabaseHas('tasks_ratings_scheduled', $payload);

    }



    /** @test */
    public function user_can_update_task_rating_scheduled()
    {

        $rating = TaskRatingsScheduled::factory()->create([
            'user_id' => $this->user->id,
            'task_id' => $this->job->id,
            'task_for_rate_id' => $this->application->id,
            'match_id' => $this->matchId ,
        ]);


        $newTaskForRate = $this->createTask();

        $payload = [
            'task_for_rate_id' => $newTaskForRate->id,
            // eventuelt 'match_id' hvis det kan opdateres
        ];


        $response = $this->actingAs($this->user)->putJson(route('api.tasks.ratings_schedules.update', $rating->id), $payload);

        $response->assertOk()
            ->assertJsonFragment([
                'task_for_rate_id' => $newTaskForRate->id,
            ]);

        $this->assertDatabaseHas('tasks_ratings_scheduled', [
            'id' => $rating->id,
            'task_for_rate_id' => $newTaskForRate->id,
        ]);


    }


    /** @test */
    public function user_can_delete_task_rating_scheduled()
    {

        $rating = TaskRatingsScheduled::factory()->create([
            'user_id' => $this->user->id,
            'task_id' => $this->job->id,
            'task_for_rate_id' => $this->application->id,
            'match_id' => $this->matchId ,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('api.tasks.ratings_schedules.destroy', $rating->id));

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks_ratings_scheduled', ['id' => $rating->id]);
    }

}
