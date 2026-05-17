<?php

namespace Modules\Tasks\Http\Requests;

use Orion\Http\Requests\Request;

class TaskRatingsScheduledRequest extends Request
{
    public function storeRules(): array
    {
        return [
            'match_id' => 'required',
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'task_for_rate_id' => 'required|integer|exists:tasks,id',
        ];
    }

    public function updateRules(): array
    {
        return [
            'match_id' => 'sometimes',
            'user_id' => 'sometimes|integer|exists:users,id',
            'task_id' => 'sometimes|integer|exists:tasks,id',
            'task_for_rate_id' => 'sometimes|integer|exists:tasks,id',
        ];
    }

    public function messages(): array
    {
        return [
            'match_id.required' => 'Match ID er påkrævet.',
            'match_id.exists' => 'Match ID findes ikke.',
            'user_id.required' => 'User ID er påkrævet.',
            'user_id.exists' => 'User ID findes ikke.',
            'task_id.required' => 'Task ID er påkrævet.',
            'task_id.exists' => 'Task ID findes ikke.',
            'task_for_rate_id.required' => 'Task for rate ID er påkrævet.',
            'task_for_rate_id.exists' => 'Task for rate ID findes ikke.',
        ];
    }
}
