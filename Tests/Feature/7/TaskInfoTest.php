<?php

namespace Modules\Tasks\Tests\Feature;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\User;
use Illuminate\Support\Facades\Artisan;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;


class TaskInfoTest extends TestExtend
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', [
            '--class' => CountrySeeder::class,
        ]);

        Artisan::call('module:seed', [
            'module' => 'Tasks',
        ]);


        $this->user = $this->createUser();

    }


    public function test_can_fetch_task_info_overview()
    {
        $response = $this->actingAs($this->user)->getJson(route('api.tasks.task.info'));

        $response->assertOk()
                 ->assertJsonStructure([
                     'data' // tilpas dette hvis din controller returnerer noget andet
                 ]);
    }
}
