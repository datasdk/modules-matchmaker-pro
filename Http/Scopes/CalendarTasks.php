<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;


trait CalendarTasks
{

    public function scopeCalendarTasks(Builder $query, int $userId)
    {

        return $query->where('type', 'job')
            ->where('status', 'live')
            ->where(function ($q) use ($userId) {

                $q->where('user_id', $userId)
                  ->orWhere(function ($q) use ($userId) {

                      $q->whereHas('hires', function ($q) use ($userId) {

                          $q->where('user_id', $userId);

                      });

                  });

            });

    }

}
