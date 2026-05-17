<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reviews\Models\Interaction;
use Illuminate\Database\Eloquent\Builder;


class TaskFavorites extends Interaction
{
   

    protected static function booted()
    {
        static::addGlobalScope('onlyFavorites', function (Builder $builder) {
            $builder->where('relation', 'favorite');
        });
    }

}
