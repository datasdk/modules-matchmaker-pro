<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;


trait OnlyHired {

    public function scopeOnlyHired($q){

        return $q->has("hires");      

   
    }
      

}