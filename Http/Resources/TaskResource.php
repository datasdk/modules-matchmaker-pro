<?php

namespace Modules\Tasks\Http\Resources;

use Orion\Http\Resources\Resource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BaseResource;
use Modules\Tasks\Models\Tasks;


class TaskResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $task = $this->resource;

        $res = $this->translateResource($task,$request);

        
        if($task->laravel_through_key){

            $parentTask = Tasks::find($task->laravel_through_key);

            $reactions = $this->getReactionsAttribute($task,$parentTask);

            if($reactions){

                $res["reactions"] = $reactions;

            }
              
        }
        
        return $res;

    }



    public function getReactionsAttribute(Tasks $task, Tasks $parentTask){


        $likes_me = $parentTask->isLiking($task);

        $dislike_me = $parentTask->isDisLiking($task);

        $likes = $task->isLiking($parentTask);

        $dislikes = $task->isDisLiking($parentTask);


        return [
            "interaction" => $likes || $dislikes,
            "interaction_with_me" => $likes_me || $dislike_me,
            "likes" => $likes,
            "dislikes" => $dislikes,
            "likes_me" => $likes_me,
            "dislike_me" => $dislike_me
        ];

    }


}
