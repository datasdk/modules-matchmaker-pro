<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Modules\Tasks\Models\Tasks;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSplitRRTest extends TestExtend
{

    use RefreshDatabase;

    
     public function test_splittest_RR_JA(): void
    {

        $res = $this->splitTest([
            "amount" => 7,
            "from" => "2025-01-01", 
            "to" => "2025-03-01",
            "should_split" => 0,
            "should_reduce" => 1
        ],[
            "amount" => 5,
            "from" => "2025-02-01", 
            "to" => "2025-04-01",
            "should_split" => 0,
            "should_reduce" => 1
        ]);


        $reduces = $res["reduces"];


        $this->assertDateMatch($reduces);
        $this->assertAmountMatch($reduces);
        $this->assertHasHired($reduces);

    }



    public function test_splittest_RR_AJ(): void
    {

        $res = $this->splitTest([
            "amount" => 5,
            "from" => "2025-02-01", 
            "to" => "2025-04-01",
            "should_split" => 0,
            "should_reduce" => 1
        ],[
            "amount" => 7,
            "from" => "2025-01-01", 
            "to" => "2025-03-01",
            "should_split" => 0,
            "should_reduce" => 1
        ]);


        $reduces = $res["reduces"];


        $this->assertDateMatch($reduces);
        $this->assertAmountMatch($reduces);
        $this->assertHasHired($reduces);

    }



    private function assertDateMatch(array $reduces): void
    {
        $this->assertSame(
            $reduces["job"]["available"]["to"],
            $reduces["application"]["available"]["to"]
        );
    }


    private function assertAmountMatch(array $reduces): void
    {
        $this->assertSame(
            $reduces["job"]["amount"],
            $reduces["application"]["amount"]
        );
    }


    private function assertHasHired(array $reduces): void
    {
        $job = Tasks::findOrFail($reduces["job"]["id"]);
        $application = Tasks::findOrFail($reduces["application"]["id"]);
        $this->assertTrue($job->hasHired($application));
    }

   
}
