<?php

namespace Modules\Tasks\Tests\Feature;

use Tests\TestCase;
use Modules\Tasks\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\TaskMatch;
use Modules\Tasks\Models\Categories;
use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;

class TaskBaseTest extends TestExtend
{

    use RefreshDatabase;
 

    public function test_auth_required()
    {
   
        $response = $this->getJson(route('api.tasks.tasks.index'));
        
        $response->assertStatus(401);

    }


    public function test_can_list_tasks()
    {

        $user = $this->createUser();

        $response = $this->actingAs($user)->getJson(route('api.tasks.tasks.index'));

        $response->assertOk();

    }


    public function test_can_show_task_info()
    {

        $user = $this->createUser();

        $response = $this->actingAs($user)->getJson(route('api.tasks.task.info'));

        $response->assertOk();

    }


    public function categories_requires_auth(){

        $response = $this->getJson(route('api.tasks.categories.index'));

        $response->assertStatus(401);

    }


    public function test_can_show_category()
    {
        
        $user = $this->createUser();

        $category = $this->createCategory();

        $response = $this->actingAs($user)->getJson(route('api.tasks.categories.show', $category->id));

        $response->assertOk();

    }
    
    
}
