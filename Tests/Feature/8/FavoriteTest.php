<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Reviews\Models\User;
use Modules\Tasks\Models\Tasks;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;


class FavoriteTest extends TestCase
{

    use RefreshDatabase;


    protected function setUp(): void
    {

        parent::setUp();

        Artisan::call('db:seed', [
            '--class' => CountrySeeder::class,
        ]);

    }

 
    public function test1_user_can_list_their_favorites()
    {

        $user = User::factory()->create();
      

        $tasks = Tasks::factory()->create();


        DB::table('interactions')->insert([
            'user_id' => $user->id,
            'subject_type' => Tasks::class,
            'subject_id' => $tasks->id,
            'relation' => 'favorite',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $response = $this->actingAs($user)->getJson(route('api.tasks.favorite.index'));

        $response->assertOk();

    }

    
    public function test_user_can_add_favorite()
    {

        $user = User::factory()->create();
     
        $tasks = Tasks::factory()->create();

        $payload = [
            'target' => 'tasks',
            'target_id' => $tasks->id,
        ];

        
        $response = $this->actingAs($user)->postJson(route('api.tasks.favorite.store'), $payload);

        $response->assertOk();


        $this->assertDatabaseHas('interactions', [
            'user_id' => $user->id,
            'subject_id' => $tasks->id,
            'subject_type' => Tasks::class,
            'relation' => 'favorite',
        ]);

    }


    public function test_user_can_toggle_favorite_off()
    {
        $user = User::factory()->create();
     

        $tasks = Tasks::factory()->create();

        DB::table('interactions')->insert([
            'user_id' => $user->id,
            'subject_type' => Tasks::class,
            'subject_id' => $tasks->id,
            'relation' => 'favorite',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'target' => 'tasks',
            'target_id' => $tasks->id,
            'favorite' => false,
        ];


        $response = $this->actingAs($user)->patchJson(route('api.tasks.favorite.update',$tasks->id), $payload);

        $response->assertOk();

        $this->assertDatabaseMissing('interactions', [
            'user_id' => $user->id,
            'subject_id' => $tasks->id,
            'subject_type' => Tasks::class,
            'relation' => 'favorite',
        ]);

    }


    public function test_user_can_remove_favorite_via_delete()
    {
        $user = User::factory()->create();
 

        $tasks = Tasks::factory()->create();

        DB::table('interactions')->insert([
            'user_id' => $user->id,
            'subject_type' => Tasks::class,
            'subject_id' => $tasks->id,
            'relation' => 'favorite',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'target' => 'tasks',
            'target_id' => $tasks->id,
        ];

        $response = $this->actingAs($user)->deleteJson(route('api.tasks.favorite.destroy',$tasks->id), $payload);

        $response->assertOk();


        $this->assertDatabaseMissing('interactions', [
            'user_id' => $user->id,
            'subject_id' => $tasks->id,
            'subject_type' => Tasks::class,
            'relation' => 'favorite',
        ]);

    }
       
}
