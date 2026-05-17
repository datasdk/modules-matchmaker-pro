<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait WithoutHired
{
    // Scope for filtering tasks without the 'hires' relation and where task_id is 1
    public function scopeWithoutHired($q)
    {
     
        $req = request();

        return $q->whereDoesntHave('hiredBy');

     

    }
}
