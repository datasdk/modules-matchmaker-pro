<?php

namespace Modules\Tasks\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Tasks\Models\Tasks;

class MatchCreated
{

    use SerializesModels;

    
    public $task1;
    public $task2;
    public $job;
    public $application;
    public $match_id;


    public function __construct(array $data)
    {

        
        $this->match_id = $data["match_id"];

        $this->task1 = $data["tasks"]["me"];

        $this->task2 = $data["tasks"]["other"];

        $this->job = $data["tasks"]["job"];

        $this->application = $data["tasks"]["application"];
        

    }

}
