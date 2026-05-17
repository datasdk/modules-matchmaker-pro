<?php

namespace Modules\Tasks\Http\Requests;

use Orion\Http\Requests\Request;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\MatchService;

class VoteRequest extends Request
{
    // Validation rules for voting
    public function storeRules(): array
    {

        $this->storeValidation();
       
        return [
            "task_id" => "required|exists:tasks,id",
            "like_task_id" => "required|exists:tasks,id",      // ID of task being voted on
            "vote" => "required|boolean",                      // Must be true or false
            "should_split" => "sometimes|integer",             // Optional: whether to split job
            "should_reduce" => "sometimes|integer",            // Optional: whether to reduce job
            "message" => "nullable|string|max:1000",           // Optional message
        ];

    }

    // Validation rules for resetting votes
    public function deleteRules(): array
    {

        return [
            "id" => "required|exists:tasks,id",                // Task ID to reset vote for
            "message" => "nullable|string|max:1000",
        ];

    }

    // Optional: Custom error messages
    public function messages(): array
    {

        return [
            'like_task_id.required' => 'Du skal angive en opgave at stemme på.',
            'like_task_id.exists' => 'Den valgte opgave findes ikke.',

            'vote.required' => 'Stemmen er påkrævet.',
            'vote.boolean' => 'Stemmen skal være sand (true) eller falsk (false).',

            'should_split.integer' => 'Split-flag skal være et tal.',
            'should_reduce.integer' => 'Reducer-flag skal være et tal.',

            'message.string' => 'Beskeden skal være en tekst.',
            'message.max' => 'Beskeden må ikke være længere end 1000 tegn.',

            'id.required' => 'Task ID er påkrævet for at nulstille stemmer.',
            'id.exists' => 'Den opgave, du forsøger at nulstille, findes ikke.',
        ];

    }


    public function storeValidation(){


        $user = $this->user();

        $task = Tasks::find($this->task_id);

        $likeTask = Tasks::find($this->like_task_id);

        if(!$task || !$likeTask){ 
            // returns null and returns the standard validation rules
            return null;
        }


        $quality = app(MatchService::class)->getMatchQuality($task, $likeTask);


        if($quality > 1 && $this->boolean("vote")){

            if (!$this->boolean("should_split") && !$this->boolean("should_reduce")) {

                abort(400, 'Angiv venligst om opslagene skal reduceres eller splittes');
    
            }

        }

 

        if ($this->boolean("should_split") && $this->boolean("should_reduce")) {

            abort(400, 'Du kan ikke både splitte og reducere opslag');

        }


        if ($this->boolean("vote") && $task->status !== "live") {

            abort(403, 'Du kan ikke acceptere dette opslag da dit eget opslag ikke er tilgængeligt');

        }


        if ($this->boolean("vote") && $likeTask->status !== "live") {

            abort(403, 'Du kan ikke acceptere dette opslag, da det er lukket eller taget af en anden');

        }


        if ($user->id !== $task->user_id) {

            abort(403, 'Du er ikke tilknyttet denne sag.');

        }


        if ($task->id === $likeTask->id) {

            abort(400, 'Du kan ikke stemme på din egen opgave.');

        }

    }


}
