<?php

namespace App\Http\Resources;

use Orion\Http\Resources\Resource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BaseResource;

class SplitResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $resource = $this->resource;
        
        // Oversæt ressourcen hvis nødvendigt
        $res = $this->translateResource($resource,$request);

        
    
        return $res;

    }

}
