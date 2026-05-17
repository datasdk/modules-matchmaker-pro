<?php

namespace Modules\Tasks\Tests\Feature;

use Modules\Tasks\Tests\Contracts\TaskTest as TestExtend;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSplitNNTest extends TestExtend
{
   
    use RefreshDatabase;
    

    public function test_splittest_NN()
    {

        $this->assertNoSplitNoReduce(
            [
                "amount" => 5,
                "from" => "2025-01-01", 
                "to" => "2025-02-01"
            ],
            [
                "amount" => 5,
                "from" => "2025-01-01", 
                "to" => "2025-02-01"
            ]
        );
        
    }



     /**
     * Sammenlign job og ansøgning – tjek at de matcher på dato og amount.
     */
    private function assertNoSplitNoReduce(array $job, array $application): void
    {
        $res = $this->splitTest($job, $application);

        $hire = $res["hire"];
        $job = $hire["job"];
        $application = $hire["application"];

        $this->assertSame(
            $application["available"]["from"],
            $job["available"]["from"],
            'Startdatoer skal være ens'
        );

        $this->assertSame(
            $application["available"]["to"],
            $job["available"]["to"],
            'Slutdatoer skal være ens'
        );

        $this->assertSame(
            $application["amount"],
            $job["amount"],
            'Mængden skal være ens'
        );
    }

}
