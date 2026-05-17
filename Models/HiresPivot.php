<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Tasks\Models\Hires;

class HiresPivot extends Pivot{


    protected $table = "tasks_hires";
    
    
    public function task(){

        return $this->belongsTo(Tasks::class,"task_id");

    }

    public function hired(){

        return $this->belongsTo(Hires::class,"task_id");

    }

    
}