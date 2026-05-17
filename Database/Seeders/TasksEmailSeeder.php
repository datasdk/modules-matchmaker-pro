<?php

namespace Modules\Tasks\Database\Seeders;

use Modules\Email\Database\Seeders\Contracts\AbstractEmailTemplateSeeder;


class TasksEmailSeeder extends AbstractEmailTemplateSeeder
{
    protected string $moduleName = 'tasks';

    protected array $templates = [
        'applicant-hired-by-job'        => \Modules\Tasks\Contracts\Emails\ApplicantHiredByJob::class,
        'job-hired-applicant'           => \Modules\Tasks\Contracts\Emails\JobHiredApplicant::class,
        'job-shoud-rate-applicant'      => \Modules\Tasks\Contracts\Emails\JobShouldRateApplicant::class,
        'applicant-shoud-rate-job'      => \Modules\Tasks\Contracts\Emails\ApplicantShouldRateJob::class,
        'applicant-has-job-reminder'    => \Modules\Tasks\Contracts\Emails\ApplicantHasJobReminder::class,
        'job-has-applicant-reminder'    => \Modules\Tasks\Contracts\Emails\JobHasApplicantReminder::class,
        'job-created'                   => \Modules\Tasks\Contracts\Emails\JobCreated::class,
        'application-created'           => \Modules\Tasks\Contracts\Emails\ApplicationCreated::class,
        'job-got-interaction'           => \Modules\Tasks\Contracts\Emails\JobGotInteraction::class,
        'applicant-got-interaction'     => \Modules\Tasks\Contracts\Emails\ApplicantGotInteraction::class,
    ];
}
