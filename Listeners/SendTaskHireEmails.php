<?php

namespace Modules\Tasks\Listeners;

use Modules\Email\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Modules\Tasks\Models\Tasks;
use Modules\Media\Services\PDFGenerator;
use Carbon\Carbon;

class SendTaskHireEmails
{
    
    public function handle($event)
    {
        
        $job = $event->job->load('contact', 'address', 'available', 'user.contact','user.address');
        $application = $event->application->load('contact', 'address', 'available', 'user.contact','user.address');


        $params = $this->generateParameters($job, $application);
        $attachments = $this->generateAttachments($job, $application, $params);


        $emails = [
            $this->sendEmail($application->user->email, 'applicant-hired-by-job', $params, $attachments['application']),
            $this->sendEmail($job->user->email, 'job-hired-applicant', $params, $attachments['job']),
        ];


        $scheduledEmails = [
            $this->sendJobReminder($job, $params, $attachments),
            $this->sendApplicantReminder($application, $params, $attachments),
        ];


        return [
            "emails" => $emails,
            "scheduledEmails" => $scheduledEmails,
        ];

    }


    private function sendEmail(string $to, string $template, array $params = [], array $attachments = [])
    {

        try {

            return app(EmailService::class)->send([
                'to' => $to,
                'template' => $template,
                'params' => $params,
                'attachments' => $attachments,
            ]);

        } catch (\Exception $e) {

            Log::warning("Email sending failed: $template", [
                'email' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;

        }

    }


    private function sendJobReminder(Tasks $task, array $params, array $attachments)
    {
        
        if($task->available->from->lt(now())){ return null; }

        $params['send_after'] = $task->available->from->subDay();

        return $this->sendEmail($task->user->email, 'job-has-applicant-reminder', $params, $attachments['job']);

    }


    private function sendApplicantReminder(Tasks $task, array $params, array $attachments)
    {

        if($task->available->from->lt(now())){ return null; }

        $params['send_after'] = $task->available->from->subDay();

        return $this->sendEmail($task->user->email, 'applicant-has-job-reminder', $params, $attachments['application']);

    }


    

    private function generateParameters(Tasks $job, Tasks $application): array
    {
        $params = [
            'job' => $job->translate(),
            'applicant' => $application->translate(),
        ];

     
        return $params;
    }



    private function generateAttachments(Tasks $job, Tasks $application, array $params = []): array
    {

        return [
            'job' => [
                $this->generateTaskPDF($job, $params)
            ],
            'application' => [
                $this->generateTaskPDF($application, $params)
            ],
        ];

    }


    private function generateTaskPDF(Tasks $task, array $params = []): string
    {
        $view = 'tasks::pdf/task-' . $task->type;

        return app(PDFGenerator::class)->generatePDF($view, $params, true);
    }

}
