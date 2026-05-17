<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Modules\Tasks\Models\Tasks;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ApiSplitSSTest extends TestExtend
{

    use RefreshDatabase;

    
    public function test_splittest_SS_JA(): void
    {

        $res = $this->splitTest([
            "amount" => 7,
            "from" => "2025-01-01",
            "to" => "2025-03-01",
            "should_split" => 1,
            "should_reduce" => 0
        ], [
            "amount" => 5,
            "from" => "2025-02-01",
            "to" => "2025-04-01",
            "should_split" => 1,
            "should_reduce" => 0
        ]);


        $split = $res["split"];

        $hire  = $res["hire"];


        $job = $split["job"];
        $app = $split["application"];

        // Job
        $this->assertSame("2025-01-01 00:00:00", $job['merge_backward']["available"]["from"]);
        $this->assertSame("2025-01-31 00:00:00", $job['merge_backward']["available"]["to"]);

        $this->assertSame("2025-02-01 00:00:00", $job['allocation']["available"]["from"]);
        $this->assertSame("2025-03-01 00:00:00", $job['allocation']["available"]["to"]);

        $this->assertSame("2025-02-01 00:00:00", $job['residual']["available"]["from"]);
        $this->assertSame("2025-03-01 00:00:00", $job['residual']["available"]["to"]);

        $this->assertNull($job['merge_forward']);

        // Application
        $this->assertNull($app['merge_backward']);

        $this->assertSame($job['allocation']["available"]["from"], $app['allocation']["available"]["from"]);
        $this->assertSame($job['allocation']["available"]["to"], $app['allocation']["available"]["to"]);

        $this->assertNull($app['residual']);
    
        $this->assertSame("2025-03-02 00:00:00", $app['merge_forward']["available"]["from"]);
        $this->assertSame("2025-04-01 00:00:00", $app['merge_forward']["available"]["to"]);

        $this->assertSame($hire['job']["amount"], $hire['application']["amount"]);

        $this->assertHasHired($job['allocation']["id"], $app['allocation']["id"]);


    }


    public function test_splittest_SS_AJ(): void
    {

        $res = $this->splitTest([
            "amount" => 5,
            "from" => "2025-02-01",
            "to" => "2025-04-01",
            "should_split" => 1,
            "should_reduce" => 0
        ],[
            "amount" => 7,
            "from" => "2025-01-01",
            "to" => "2025-03-01",
            "should_split" => 1,
            "should_reduce" => 0
        ]);


        $split = $res["split"];

        $hire  = $res["hire"];

        $job = $split["job"];

        $app = $split["application"];


        $this->assertSame("2025-01-01 00:00:00", $app['merge_backward']["available"]["from"]);
        $this->assertSame("2025-01-31 00:00:00", $app['merge_backward']["available"]["to"]);

        $this->assertSame("2025-02-01 00:00:00", $app['allocation']["available"]["from"]);
        $this->assertSame("2025-03-01 00:00:00", $app['allocation']["available"]["to"]);

        $this->assertSame("2025-02-01 00:00:00", $app['residual']["available"]["from"]);
        $this->assertSame("2025-03-01 00:00:00", $app['residual']["available"]["to"]);

        $this->assertNull($app['merge_forward']);

        // Application
        $this->assertNull($job['merge_backward']);

        $this->assertSame($app['allocation']["available"]["from"], $job['allocation']["available"]["from"]);
        $this->assertSame($app['allocation']["available"]["to"], $job['allocation']["available"]["to"]);

        $this->assertNull($job['residual']);

        $this->assertSame("2025-03-02 00:00:00", $job['merge_forward']["available"]["from"]);
        $this->assertSame("2025-04-01 00:00:00", $job['merge_forward']["available"]["to"]);

        $this->assertSame($hire['job']["amount"], $hire['application']["amount"]);

        $this->assertHasHired($job['allocation']["id"], $app['allocation']["id"]);


    }



    protected function assertHasHired(int $jobId, int $applicationId): void
    {

        $job = Tasks::findOrFail($jobId);

        $application = Tasks::findOrFail($applicationId);

        $this->assertTrue($job->hasHired($application));

    }

}
