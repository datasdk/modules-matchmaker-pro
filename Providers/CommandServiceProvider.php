<?php

namespace Modules\Tasks\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;


class CommandServiceProvider extends ServiceProvider
{

    public function boot()
    {
        
        $this->registerTasksCommands();


    }


 
    protected function registerTasksCommands()
    {

        $this->commands([
            \Modules\Tasks\Console\Commands\AddMissingCounters::class,
            \Modules\Tasks\Console\Commands\UpdateTaskStatus::class,

            // demo data
            \Modules\Tasks\Console\Commands\ClearDemoData::class,
            \Modules\Tasks\Console\Commands\GiveTaskEmailPermission::class
        ]);

        
     

    }
}
