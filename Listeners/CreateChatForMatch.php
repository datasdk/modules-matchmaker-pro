<?php

namespace Modules\Tasks\Listeners;

use Modules\Chat\Services\ChatService;
use Modules\Tasks\Events\MatchCreated;

class CreateChatForMatch
{
    public function handle(MatchCreated $event)
    {


        $chatname = $event->match_id;


        [$title, $description] = $this->createTitleAndDescription($event->task1, $event->task2);


        $chatService = app(ChatService::class);
        
        $conversation = $chatService->create($chatname, $title, $description);


        $chatService->addUsers($conversation, [
            $event->task1->user->id,
            $event->task2->user->id,
        ]);


        $conversation->update([
            "data" => [
                "match_id" => $event->match_id,
                "task1" => $event->task1->toArray(),
                "task2" => $event->task2->toArray(),
            ],
        ]);


        return [
            'conversation' => $conversation->load("participants")
        ];

    }


    private function createTitleAndDescription($task1, $task2): array
    {

        $task1Data = [
            "case" => $task1->case_number,
            "company" => $task1->company?->name ?? null,
            "user"    => trim($task1->user->first_name . " " . $task1->user->last_name),
        ];


        $task2Data = [
            "case" => $task2->case_number,
            "company" => $task2->company?->name ?? null,
            "user"    => trim($task2->user->first_name . " " . $task2->user->last_name),
        ];


        $title = ($task1Data["company"] ?? $task1Data["user"]) 
            . " og " 
            . ($task2Data["company"] ?? $task2Data["user"]);

        $description = "(Sag nr: {$task1Data["case"]}) og (Sag nr: {$task2Data["case"]})";


        return [$title, $description];

    }


}
