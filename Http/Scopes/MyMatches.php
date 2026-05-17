<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;


trait MyMatches {

    public function scopeMyMatches($q,$user_id){

        return $q->whereHas("matches",function($q) use($user_id){

            $q->where("user_id",$user_id);

        });      

    }


    


}