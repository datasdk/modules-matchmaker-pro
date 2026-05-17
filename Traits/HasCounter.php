<?php

namespace Modules\Tasks\Traits;

use Turahe\Counters\Facades\Counters;
use Turahe\Counters\Models\Counterable;
use Turahe\Counters\Traits\HasCounter as  BaseHasCounter;
use Illuminate\Database\Eloquent\Builder;


trait HasCounter
{

    use BaseHasCounter;


    public function counter()
    {
        return $this->morphOne(Counterable::class, 'counterable')
            ->withDefault([
                'value' => 0, // Hvis null, sæt standard til 0
            ]);
    }


  

}