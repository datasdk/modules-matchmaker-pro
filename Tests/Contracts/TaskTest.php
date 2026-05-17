<?php

namespace Modules\Tasks\Tests\Contracts;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Modules\Tasks\Models\Tasks;
use App\Models\User;
use Modules\Tasks\Models\Categories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DataSDK\Addresses\Database\Seeders\CountrySeeder;
use Modules\Tasks\Services\TaskService;

abstract class TaskTest extends TestCase
{

    public $job;
    public $application;
    public $isMatching;
    public $user;
    public $user2;


    protected function setUp(): void
    {

        parent::setUp();

        Artisan::call("db:seed", ["--class" => CountrySeeder::class]);
        Artisan::call("module:seed tasks");

    }


    protected function makeMatch(array $jobParams = [], array $applicationParams = []): ?array
    {

        $user = $this->createUser();
        $user2 = $this->createUser();


        $job = $this->createTask([
            "type"      => "job",
            "user_id"   => $user->id,
            "amount"    => $jobParams["amount"] ?? 1,
            "status"    => "live"
        ])->set_available([
            "from"  => $jobParams["from"] ?? now()->addMonth(),
            "to"    => $jobParams["to"] ?? now()->addMonths(2),
        ]);


        $application = $this->createTask([
            "type"      => "application",
            "user_id"   => $user2->id,
            "amount"    => $applicationParams["amount"] ?? 1,
            "status"    => "live"
        ])->set_available([
            "from"  => $applicationParams["from"] ?? now()->addMonth(),
            "to"    => $applicationParams["to"] ?? now()->addMonths(2),
        ]);


        // Vote from job owner
        $this->makeVote($job, $application, $user, [
            "should_split"  => $jobParams["should_split"] ?? 0,
            "should_reduce" => $jobParams["should_reduce"] ?? 0,
        ]);


        // Vote from applicant
        $response = $this->makeVote($application, $job, $user2, [
            "should_split"  => $applicationParams["should_split"] ?? 0,
            "should_reduce" => $applicationParams["should_reduce"] ?? 0,
        ]);


        $res = $response['match'] ? $response : null;

        $this->job = $job->refresh();
        $this->application = $application->refresh();
        $this->isMatching = !is_null($res);
        $this->user = $user;
        $this->user2 = $user2;

        return $res;

    }


    protected function splitTest(array $jobParams, array $applicationParams)
    {

        $response = $this->makeMatch($jobParams, $applicationParams);


        if (!$response) {

            $this->fail('No match was created during split test');

        }


        return $response['events'][0]; // first event result

    }


    protected function makeVote(Tasks $task1, Tasks $task2, User $user, array $customParams = [])
    {

        $params = array_merge([
            "task_id"      => $task1->id,
            "like_task_id" => $task2->id,
            "vote"         => 1,
        ], $customParams);


        $response = $this->actingAs($user)->postJson(
            route('api.tasks.vote.store'),
            $params
        );


        $response->assertSuccessful();

        return $response->json();

    }

    public function createUser(array $params = [])
    {

        return User::factory()->create($params)->load('address', 'contact');

    }


    public function createTask(array $params = [])
    {

        return Tasks::factory()->create(array_merge([
            'status' => 'live',
            'amount' => 1,
        ], $params));

    }


    public function createCategory(array $params = [])
    {

        return Categories::factory()->create($params);

    }


    protected function findJob(Tasks $task1, Tasks $task2)
    {

        return app(TaskService::class)->findJob($task1, $task2);

    }


    protected function findApplication(Tasks $task1, Tasks $task2)
    {

        return app(TaskService::class)->findApplication($task1, $task2);

    }


    protected function assertMatchCreated(?array $response)
    {

        $this->assertNotNull($response, 'Expected a match to be created but got null');

        $this->assertTrue($response['match'] ?? false, 'Match flag was false');

    }

}
