<?php

namespace Modules\Tasks\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Modules\Tasks\Events\TaskGotInteraction;
use Modules\Tasks\Models\Tasks;
use Modules\Email\Services\EmailService;
use Modules\Media\Services\PDFGenerator;

class SendTaskInteractionEmail
{

    public function handle(TaskGotInteraction $event)
    {

        
        $task = $event->task->load('address', 'available', 'company', 'categories', 'user.contact', 'user.address');

        $mytask = $event->mytask->load('address', 'available', 'company', 'categories', 'user.contact', 'user.address');


        if(!$task->user){ return; }


        $params = $this->generateParameters($mytask,$task);
            
        $template  = $task->isJob() ? 'job-got-interaction' : 'applicant-got-interaction';

        $to = $mytask->user->email;



        return $this->sendEmail(
            $to,
            $template,
            $params,
            $this->generateAttachments($task)
        );


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


    private function generateParameters(Tasks $mytask,Tasks $task): array
    {

        $params = [
            'mytask' => $mytask->translate(),
            'task' => $task->translate(),
        ];

      
        return $params;

    }


    private function generateAttachments(Tasks $task): array
    {
        $view = 'tasks::pdf/task-' . $task->type;

        if (!View::exists($view)) {
            return [];
        }

        return [
            $this->generateTaskPDF($task)
        ];
    }


    private function generateTaskPDF(Tasks $task): string
    {
        $view = 'tasks::pdf/task-' . $task->type;
        $key = $task->isJob() ? 'job' : 'applicant';

        return app(PDFGenerator::class)->generatePDF($view, [
            $key => $task->translate(),
        ], true);
    }

}
