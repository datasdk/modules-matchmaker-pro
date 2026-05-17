<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Tasks;

class TaskStatusCodeService
{
    public Tasks $task;

    public const LIVE = 'live';
    public const ONGOING = 'ongoing';
    public const PENDING = 'pending';
    public const HOLD = 'hold';
    public const COMPLETE = 'complete';
    public const SPLITTED = 'splitted';
    public const DRAFT = 'draft';
    public const CLOSED = 'closed';
    public const CANCELLED = 'cancelled';

 
    public static function allStatuses(): array
    {
        return [
            self::LIVE,
            self::ONGOING,
            self::PENDING,
            self::HOLD,
            self::COMPLETE,
            self::SPLITTED,
            self::DRAFT,
            self::CLOSED,
            self::CANCELLED,
        ];
    }

    public static function activeStatuses(): array
    {
        return [
            self::LIVE,
            self::ONGOING,
            self::PENDING,
        ];
    }

    public static function inactiveStatuses(): array
    {
        return [
            self::COMPLETE,
            self::CLOSED,
            self::CANCELLED,
        ];
    }

   
}
