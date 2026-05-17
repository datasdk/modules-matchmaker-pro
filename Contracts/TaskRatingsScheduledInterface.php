<?php

namespace Modules\Tasks\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface TaskRatingsScheduledInterface
{
    public function task(): BelongsTo;

    public function taskForRate(): BelongsTo;

    public function user(): BelongsTo;
}
