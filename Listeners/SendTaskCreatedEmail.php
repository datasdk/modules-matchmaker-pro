<?php

namespace Modules\Tasks\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Tasks\Events\TaskCreated;
use Modules\Tasks\Models\Tasks;
use Modules\Email\Services\EmailService;
use App\Models\User;

class SendTaskCreatedEmail
{
    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event)
    {
       

        $email = config("tasks.settings.task_create_notify_admin_email");


        if($email){


            // Load relations for the task
            $task = $event->task->load(
                'address',
                'available',
                'company',
                'categories',
                'user.contact',
                'user.address'
            );


            // Generer parametre til e-mail
            $params   = $this->generateParameters($task);

            $template = $task->isJob() ? 'job-created' : 'application-created';
            

           $this->sendEmail($user->email, $template, $params);


        }
        
       
    }

    
    /**
     * Find alle users med en given permission via Spatie
     */
    private function getUsersWithPermission(string $permission)
    {
        return User::permission($permission)->get();
    }


    /**
     * Send e-mail
     */
    private function sendEmail(string $to, string $template, array $params = [], array $attachments = [])
    {
        try {
            return app(EmailService::class)->send([
                'to'       => $to,
                'template' => $template,
                'params'   => $params,
                'attachments'   => $attachments,
            ]);
        } catch (\Exception $e) {
            Log::warning("Email sending failed: $template", [
                'email' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generer e-mail parametre
     */
    private function generateParameters(Tasks $task): array
    {
        $params = [
            'task' => $task->translate(),
            'type' => $task->isJob() ? "Projekt" : "Mandskab"
        ];

    
        return $params;
    }
}
