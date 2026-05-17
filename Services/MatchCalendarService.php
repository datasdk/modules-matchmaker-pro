<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Tasks;
use Modules\Calendar\Services\CalendarService;
use Modules\Tasks\Services\MatchService;
use Modules\Calendar\Services\ColorService;
use Modules\Tasks\Models\Calendar;


class MatchCalendarService
{
  

    public function create(Tasks $task, ?array $data, string $matchId){

        
        


        $title = $this->makeCalendarTitle($task);

        $description = $this->makeCalendarDescription($task);

        $color = $this->getRandomColor($task);


        $calendar = Calendar::create([
            'type'        => 'tasks',
            'color'       => $color,
            'user_id'     => $task->user_id,
            'title'       => $title,
            'description' => $description,
            'link'        => null,
            'task_id'     => $task->id,
            'data'        => $data
        ]);
        
        $calendar->set_available([
            'from'        => $task->available->from,
            'to'          => $task->available->to,
        ]);

        $calendar->refresh();


        return $calendar;
    }

/*
    public function update($id, Tasks $task, string $matchId, array $newData = []): ?object
    {
     

        $calendarService = app(CalendarService::class);

        $event = $calendarService->findByUid($uid);

        if (!$event) {
            return null;
        }

        $complete = $this->isComplete($task);

        return $calendarService->update($event->id, [
            'title'       => $this->makeCalendarTitle($task),
            'description' => $this->makeCalendarDescription($task),
            'color'       => $this->getRandomColor($complete),
            'link'        => null,
            'user_id'     => $task->user_id,
            'type'        => 'tasks',
            'data'        => $newData ?: $event->data,
        ])->available([
            'from' => $task->available->from,
            'to'   => $task->available->to,
        ]);
    }

*/


    public function delete(Tasks $task, string $matchId): bool
    {

        $uid = $matchId . '-' . $task->user->id;

        $calendarService = app(CalendarService::class);

        $event = $calendarService->findByUid($uid);

        if (!$event) {

            return false;

        }

        return $calendarService->delete($event->id);
    }

   

    private function getRandomColor(Tasks $task): string
    {
        
        $isComplete = $this->isComplete($task);

        $colorService = app(ColorService::class);

        return $isComplete ? $colorService->randomGreen() : $colorService->randomBlue();

    }



    private function makeCalendarTitle(Tasks $task): array
    {
        $type = $task->type === 'job' ? 'Projekt' : 'Mandskab';

        $isComplete = $this->isComplete($task);
        $checkMark  = $isComplete ? '&#10003; ' : '';

        $locales = config('app.locales', ['da', 'en']);
        $titles = [];

        foreach ($locales as $locale) {

            $title = sprintf(
                '%s%s# %s - %s - %s',
                $checkMark,
                $task->id,
                $type,
                $task->getTranslation('name', $locale),
                $task->address->city
            );

            $titles[$locale] = $title;
        }

        return $titles;
    }



    private function makeCalendarDescription(Tasks $task){

        $locales = config('app.locales', ['da', 'en']);

        $description = [];


        foreach ($locales as $locale) {

            $description[$locale] = __('tasks::calendar/event.description', [
                'name' => $task->name,
            ], $locale);

        }


        return $description;

    }

    private function isComplete(Tasks $task){

        return $task->hires_count >= $task->amount;

    }

}
