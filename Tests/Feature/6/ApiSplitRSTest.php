<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ApiSplitRSTest extends TestExtend
{

    use RefreshDatabase;

    
    public function test_splittest_RS_JA(): void
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
            "should_split" => 0,
            "should_reduce" => 1
        ]);


        $allocation = $res["allocation"];

        $reduce = $res["reduce"];


        $this->assertDateMatch($allocation, $reduce);

        $this->assertAmountMatch($allocation, $reduce);

        $this->assertHasHired($allocation, $reduce);

    }



     public function test_splittest_RS_AJ(): void
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
            "should_split" => 1,
            "should_reduce" => 0
        ]);


        $allocation = $res["allocation"];

        $reduce = $res["reduce"];


        $this->assertDateMatch($allocation, $reduce);

        $this->assertAmountMatch($allocation, $reduce);

        $this->assertHasHired($allocation, $reduce);

    }



    protected function assertDateMatch(array $allocation, array $reduce): void
    {

        $this->assertSame($allocation["available"]["from"], $reduce["available"]["from"]);

        $this->assertSame($allocation["available"]["to"], $reduce["available"]["to"]);

    }


    protected function assertAmountMatch(array $allocation, array $reduce): void
    {

        $this->assertSame($allocation["amount"], $reduce["amount"]);

    }


    protected function assertHasHired(array $allocation, array $reduce): void
    {

        $task1 = Tasks::findOrFail($allocation["id"]);

        $task2 = Tasks::findOrFail($reduce["id"]);

        $job = app(TaskService::class)->findJob($task1, $task2);

        $application = app(TaskService::class)->findApplication($task1, $task2);

        $this->assertTrue($job->hasHired($application));

    }


}
