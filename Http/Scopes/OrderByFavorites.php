<?php

namespace Modules\Tasks\Http\Scopes;

use Illuminate\Support\Facades\DB;

use Modules\Tasks\Models\User;
use Modules\Companies\Models\Companies;


trait OrderByFavorites {



    public function scopeOrderByFavorites($q,$type = "user"){


        if($type === "user"){ 

            $field = 'user_id';
            $subject_type = User::class; 

        }

        else if($type === "company"){ 

            $field = 'company_id';
            $subject_type = Companies::class; 

        } else {
            
            return false;

        }

           
        $ids = User::findOrFail(auth()->user()->id)->favorites()
        ->where("subject_type",$subject_type)->pluck("subject_id");
        

        if($ids->isNotEmpty()){

            $q->orderByRaw(DB::raw("FIELD(".$field.", ".$ids->implode(",").") DESC"));

        }        
        
   
    }
    


   

}