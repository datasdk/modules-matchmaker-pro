<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Email\Models\MailTemplates;


class TasksDatabaseSeeder extends Seeder
{

    
    public function run()
    {
      
        Model::unguard();

        $this->call([
            TasksEmailSeeder::class,
            TaskNotificationSeeder::class
        ]);

    }

}
