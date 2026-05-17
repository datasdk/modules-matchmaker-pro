<?php

namespace Modules\Tasks\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;
use Modules\Tasks\Models\Tasks;

interface MatchInterface
{
    public function hire();

    public function reject();

    public function company(): BelongsTo;

 //   public function match(): HasOneThrough;

    public function task(): BelongsTo;

    public function matchedTask(): BelongsTo;

    public function hires();

    public function hired();

    public function scopeHideRejected(Builder $query): Builder;

    public function scopeHideHired(Builder $query): Builder;

    public function scopeMe(Builder $query): Builder;

    public function scopeOther(Builder $query): Builder;
}
