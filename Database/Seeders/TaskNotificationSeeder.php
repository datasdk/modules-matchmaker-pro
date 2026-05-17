<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Firebase\Repositories\NotificationRepository;

class TaskNotificationSeeder extends Seeder
{

    protected array $drafts = [
        'applicant-has-matched-job',
        'job-has-matched-applicant',
 
    ];


    public function run()
    {
        Model::unguard();

        (new NotificationRepository)->seedDrafts($this->drafts, "tasks::notifications");

        Model::reguard();
    }
    
}
