<?php

namespace Modules\Tasks\Http\Requests;

use Orion\Http\Requests\Request;

class MatchRequest extends Request
{
    // Validation rules for creating a hire request
    public function hireRules(): array
    {
        return [
            "hire_task_id" => "required|exists:tasks,id",  // Ensure the hire_task_id exists in the tasks table
            "notification.url" => "sometimes|nullable|string",  // Ensure notification URL is a valid URL, if present
            "notification.draft_id" => "sometimes|nullable|exists:Firebase_drafts,id",  // Ensure notification draft ID exists in Firebase_drafts table
            "message" => "nullable|string|max:1000",  // Optional message when hiring
        ];
    }

    // Validation rules for rejecting a match
    public function rejectRules(): array
    {
        return [
            "reject_task_id" => "required|exists:tasks,id",  // Ensure the reject_task_id exists in the tasks table
            "message" => "nullable|string|max:1000",  // Optional message when rejecting a match
        ];
    }

    // Validation rules for casting a vote
    public function voteRules(): array
    {
        return [
            "like_task_id" => "required|exists:tasks,id",  // Ensure the like_task_id exists in the tasks table
            "vote" => "required|boolean",  // Ensure the vote is a boolean (true or false)
            "message" => "nullable|string|max:1000",  // Optional message when voting
        ];
    }

    // Validation rules for resetting a vote
    public function resetVoteRules(): array
    {
        return [
            "id" => "required|exists:tasks,id",  // Ensure the task ID exists in the tasks table
            "message" => "nullable|string|max:1000",  // Optional message when resetting a vote
        ];
    }

    // Custom validation messages
    public function messages(): array
    {
        return [
            'hire_task_id.required' => 'Hyringsopgaven er påkrævet.',
            'hire_task_id.exists' => 'Den valgte hyringsopgave findes ikke.',
            'notification.url.url' => 'URL-adressen for notifikationen er ugyldig.',
            'notification.draft_id.exists' => 'Skabelonen for notifikationen findes ikke.',
            'message.string' => 'Beskeden skal være en tekststreng.',
            'message.max' => 'Beskeden må ikke være længere end 1000 tegn.',
            
            'reject_task_id.required' => 'Afvisningsopgaven er påkrævet.',
            'reject_task_id.exists' => 'Den valgte afvisningsopgave findes ikke.',
            
            'like_task_id.required' => 'Opdeler opgaven, som skal stemmes på, er påkrævet.',
            'like_task_id.exists' => 'Den valgte opgave findes ikke.',
            'vote.required' => 'Afstemningen er påkrævet.',
            'vote.boolean' => 'Afstemningen skal være enten sand eller falsk.',
            
            'id.required' => 'Task ID er påkrævet ved nulstilling af stemme.',
            'id.exists' => 'Den valgte opgave findes ikke.',
        ];
    }
}
