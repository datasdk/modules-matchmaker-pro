<?php

namespace Modules\Tasks\Services;

use Modules\Firebase\Services\NotificationService as FirebaseService;
use Modules\Email\Services\EmailService;
use App\Models\User;

class NotificationService
{
   

    public function sendNotificationAndEmail($to, $notificationData, $emailTemplate, $params, $attachments = [])
    {
        
        $user = User::where('email', $to)->first();

        if ($user && !empty($notificationData)) {
            $this->sendPushNotification($user->id, $notificationData);

        }
        
        $this->sendEmail($to, $emailTemplate, $params, $attachments);
    }


    protected function sendPushNotification($userId, $notificationData)
    {

        (new FirebaseService())->send([
            'user_id' => $userId,
            'url' => $notificationData['url'] ?? null,
            'draft_id' => $notificationData['draft_id'] ?? null,
        ]);

    }


    protected function sendEmail($to, $template, $params, $attachments = [])
    {

        return app(EmailService::class)->send([
            'to' => $to,
            'template' => $template,
            'params' => $params,
            'attachments' => $attachments,
        ]);

    }

}
