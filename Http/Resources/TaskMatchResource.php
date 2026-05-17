<?php

namespace Modules\Tasks\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BaseResource;

use User;

class TaskMatchResource extends BaseResource
{

    protected $othersIncludes = [
        "company.user",
        "applicant.user",

        "company.categories",
        "applicant.categories",

        "company.available",
        "applicant.available",

        "company.company",
        "applicant.company"
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if($hasOther = $request->has('include') && in_array('other', $request->include)){

            $this->load( $this->othersIncludes );

        }
        
        $user_id = $request->user()->id;

        $dateFormat = "d/m/y H:i";



        $res = $this->translate($request);


        if(!is_array($res)){ $res = $res->toArray(); }
        


        if ($hasOther) {

            foreach($res["matches"] as $key => $matches){
                
                $match = $res["matches"][$key];

                if($user_id == $match["task_user_id"]){ 
                    
                    $other = "applicant"; 
                    $me = "company"; 
                
                } else { 
                    
                    $other = "company"; 
                    $me = "applicant"; 
                
                }
        

                $res["matches"][$key]["other"] = $match[$other];
                $res["matches"][$key]["me"] = $match[$me];
                
            }

        }



        return $res;
        
    
    }


   

}
