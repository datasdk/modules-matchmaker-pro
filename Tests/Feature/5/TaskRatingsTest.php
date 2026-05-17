<?php

namespace Modules\Tasks\Tests\Feature;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\User;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskRatings;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;


class TaskRatingsTest extends TestExtend
{
     use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call("db:seed", [
            "--class" => CountrySeeder::class
        ]);

        Artisan::call("module:seed tasks");
        
        $this->user = $this->createUser();
        $this->user2 = $this->createUser();

        $this->makeMatch();
    }


    public function test_can_list_ratings()
    {
        TaskRatings::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->getJson(route('api.tasks.ratings.index'));

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }


    public function test_can_view_single_rating()
    {
        $rating = TaskRatings::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->getJson(route('api.tasks.ratings.show', $rating->id));

    
        $response->assertOk();

    }


    public function test_can_create_rating_int()
    {
        $task = Tasks::factory()->create();
        $task2 = Tasks::factory()->create();

        $payload = [
            'task_id' => $task->id,
            'task_for_rate_id' => $task2->id,
            'rating' => 4,
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('api.tasks.ratings.store'), $payload);

        $response->assertCreated();

    }


  public function test_can_not_rate_ourself()
    {
        $task = Tasks::factory()->create();

        $payload = [
            'task_id' => $task->id,
            'task_for_rate_id' => $task->id, // Her rates man sig selv
            'rating' => 4,
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('api.tasks.ratings.store'), $payload);

        $response->assertStatus(500); // Korrekt metode til at teste HTTP-statuskode
    }


    public function test_can_create_rating_array()
    {

        $task = Tasks::factory()->create();
        $task2 = Tasks::factory()->create();

        $payload = [
            'task_id' => $task->id,
            'task_for_rate_id' => $task2->id,
            'ratings' => [
                ['category' => 'quality', 'stars' => 5],
                ['category' => 'punctuality', 'stars' => 4],
            ],
            'user_id' => $this->user->id,
        ];


        $response = $this->actingAs($this->user)->postJson(route('api.tasks.ratings.store'), $payload);

     

        $response->assertCreated();


    }


/*
VITRKER IKKE....
    public function test_can_update_rating_int()
    {

        $rating = TaskRatings::factory()->create([
            'user_id' => $this->user->id,
        ]);


        $response = $this->actingAs($this->user)->patchJson(route('api.tasks.ratings.update', $rating->id), [
            'rating' => 5
        ]);


        $response->assertOk();

    }



    public function test_can_update_rating_array()
    {

        $rating = TaskRatings::factory()->create([
            'user_id' => $this->user->id,
        ]);


        $response = $this->actingAs($this->user)->patchJson(route('api.tasks.ratings.update', $rating->id), [
            "ratings" => [
                ["category" => "category_1", "stars" => 4],
                ["category" => "category_2", "stars" => 1],
                ["category" => "category_3", "stars" => 5],
            ]
        ]);


        $response->assertOk();

    }
*/


    public function test_can_delete_rating()
    {
        $rating = TaskRatings::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('api.tasks.ratings.destroy', $rating->id));

        $response->assertNoContent();
    }
   
}
