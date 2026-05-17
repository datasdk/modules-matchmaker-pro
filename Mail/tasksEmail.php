<?php

namespace Modules\Tasks\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\Email;
use Illuminate\Support\Facades\Log;

class TasksEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Email $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function build(): self
    {
        $email = $this->to($this->email->to)
                      ->subject($this->email->subject)
                      ->view('email::emails.standard', [
                          'body' => $this->email->message,
                      ]);

        $attachments = $this->email->getMedia("attachments");

        foreach ($attachments as $media) {
            if ($media->getPath()) {
                $email->attach($media->getPath(), [
                    'as' => $media->file_name,
                    'mime' => $media->mime_type,
                ]);
            } else {
                Log::warning('Attachment file not found', [
                    'fileName' => $media->file_name,
                    'filePath' => $media->getPath(),
                ]);
            }
        }

        return $email;
    }
}
