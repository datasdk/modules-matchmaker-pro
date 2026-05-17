<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSplitNRTest extends TestExtend
{

    use RefreshDatabase;

    public function test_splittest_NR_JA()
    {

        $this->assertSplitReduce(
            [
                "amount" => 7,
                "from" => "2025-01-01",
                "to" => "2025-04-01",
                "should_split" => 0,
                "should_reduce" => 1
            ],
            [
                "amount" => 5,
                "from" => "2025-02-01",
                "to" => "2025-03-01",
                "should_split" => 0,
                "should_reduce" => 0
            ]
        );

    }


    public function test_splittest_NR_AJ()
    {

        $this->assertSplitReduce(
            [
                "amount" => 5,
                "from" => "2025-02-01",
                "to" => "2025-03-01",
                "should_split" => 0,
                "should_reduce" => 0
            ],
            [
                "amount" => 9,
                "from" => "2025-01-01",
                "to" => "2025-04-01",
                "should_split" => 0,
                "should_reduce" => 1
            ]
        );

    }



     /**
     * Kører split/reduce test og foretager assertions på dates og amount.
     */
    private function assertSplitReduce(array $job, array $application): void
    {

        $res = $this->splitTest($job, $application);

        $reduce = $res["reduce"];
        $hire = $res["hire"];

        $toDate = $hire["application"]["available"]["to"];
        $jobToDate = $hire["job"]["available"]["to"];
        $reduceToDate = $reduce["available"]["to"];

        // Sammenlign 'to' datoer
        $this->assertSame(
            $toDate,
            $jobToDate,
            'Job "to" date should match application "to" date'
        );

        $this->assertSame(
            $toDate,
            $reduceToDate,
            'Reduce "to" date should match application "to" date'
        );

        // Sammenlign amount
        $this->assertSame(
            $hire["job"]["amount"],
            $hire["application"]["amount"],
            'Job and application amount should be equal'
        );

        
    }

}
