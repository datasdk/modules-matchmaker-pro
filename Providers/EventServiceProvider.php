<?php

namespace Modules\Tasks\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Modules\Tasks\Events\MatchCreated::class => [
            \Modules\Tasks\Listeners\HandleSplitTasks::class,
            \Modules\Tasks\Listeners\CreateChatForMatch::class,
            \Modules\Tasks\Listeners\SendMatchCreatedNotification::class    
        ],
        \Modules\Tasks\Events\TaskHired::class => [
            \Modules\Tasks\Listeners\CreateTaskRatings::class,
            \Modules\Tasks\Listeners\SendTaskHireEmails::class,
            \Modules\Tasks\Listeners\CreateCalendarEntryFromMatch::class
        ],
        \Modules\Tasks\Events\TaskCreated::class => [
            \Modules\Tasks\Listeners\SendTaskCreatedEmail::class,
        ],
        \Modules\Tasks\Events\TaskGotInteraction::class => [
            \Modules\Tasks\Listeners\SendTaskInteractionEmail::class
        ]
    ];
}
