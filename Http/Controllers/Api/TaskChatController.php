<?php

namespace Modules\Tasks\Http\Controllers\Api;


use Modules\Chat\Http\Controllers\Api\ConversationController as OrigChatController;
use Modules\Tasks\Models\Conversation;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Http\Resources\TaskChatResource;


class TaskChatController extends OrigChatController {
        

    protected $model = Conversation::class;

    protected $resource = TaskChatResource::class;

   
    protected $includes = [

        "task1.address",
        "task1.addresses",
        "task1.contact",
        "task1.available",
        "task1.category",
        "task1.categories",
        "task1.user",

        "task2.address",
        "task2.addresses",
        "task2.contact",
        "task2.available",
        "task2.category",
        "task2.categories",
        "task2.user",

        "messages", 
        "last_message", 
        "participants.messageable", 
        "participants",
        "match"
    ];






}
