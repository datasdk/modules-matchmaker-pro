<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSplitNSTest extends TestExtend
{
  
    use RefreshDatabase;
 

    public function test_splittest_SR_JA(): void
    {

        $res = $this->splitTest([
            "amount" => 9,
            "from" => "2025-01-01", 
            "to" => "2025-04-01",
            "should_split" => 1,
            "should_reduce" => 0
        ],[
            "amount" => 5,
            "from" => "2025-02-01", 
            "to" => "2025-03-01",
            "should_split" => 0,
            "should_reduce" => 0
        ]);


        $this->assertSplitDates($res["split"]);

        $this->assertAmountMatch($res["hire"]);


    }


    public function test_splittest_SR_AJ(): void
    {


        $res = $this->splitTest([
            "amount" => 5,
            "from" => "2025-02-01", 
            "to" => "2025-03-01",
            "should_split" => 0,
            "should_reduce" => 0
        ],[
            "amount" => 9,
            "from" => "2025-01-01", 
            "to" => "2025-04-01",
            "should_split" => 1,
            "should_reduce" => 0
        ]);


        $this->assertSplitDates($res["split"]);

        $this->assertAmountMatch($res["hire"]);


    }


      /**
     * Tjekker om split-resultatet matcher de forventede datoer.
     */
    private function assertSplitDates(array $split): void
    {

        $this->assertSame("2025-01-01 00:00:00", $split['merge_backward']["available"]["from"]);
        $this->assertSame("2025-01-31 00:00:00", $split['merge_backward']["available"]["to"]);

        $this->assertSame("2025-02-01 00:00:00", $split['allocation']["available"]["from"]);
        $this->assertSame("2025-03-01 00:00:00", $split['allocation']["available"]["to"]);

        $this->assertSame("2025-02-01 00:00:00", $split['residual']["available"]["from"]);
        $this->assertSame("2025-03-01 00:00:00", $split['residual']["available"]["to"]);

        $this->assertSame("2025-03-02 00:00:00", $split['merge_forward']["available"]["from"]);
        $this->assertSame("2025-04-01 00:00:00", $split['merge_forward']["available"]["to"]);

    }

    
   /**
     * Tjekker at amounts matcher mellem job og application.
     */
    private function assertAmountMatch(array $hire): void
    {

        $this->assertSame($hire['job']["amount"], $hire['application']["amount"]);

    }

}
