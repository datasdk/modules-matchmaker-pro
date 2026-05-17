<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;


trait HideRejected {



    public function scopeHideRejected($q){

        return $q->whereHas('matches', function (Builder $query) {
            $query->where('rejected', 0);
        });
            
   
    }
      

}