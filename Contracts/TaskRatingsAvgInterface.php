<?php

namespace Modules\Tasks\Contracts;

use Illuminate\Database\Eloquent\Model;

interface TaskRatingsAvgInterface
{
    public static function updateOrCreateRating(string $subjectType, int $subjectId, float $averageRating);

    public static function findAvgRating(Model $model): ?float;
}
