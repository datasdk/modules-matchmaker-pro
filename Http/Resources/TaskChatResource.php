<?php

namespace Modules\Tasks\Http\Resources;


use App\Http\Resources\BaseResource;
use App\Models\User;
use Modules\Chat\Models\Conversation;
use Modules\Tasks\Models\Tasks;


class TaskChatResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     public $preserveKeys = true;

 


    public function toArray($req)
    {

      $request = request();


      if (!$this->resource instanceof Conversation) {

        abort(500, "The resource must be an instance of the Conversation class.");
        
      }
    
          
      $myself = true;//$req->user()->id === $this->participation_id;
      $res = $req;


      $task_1 = Tasks::find($this->data["task1"]);
      $task_2 = Tasks::find($this->data["task2"]);


      $participants = $this->participants->map(function($res) use($task_1,$task_2){


        $request = request();

        $m = $res->messageable;
        
        if($task_1?->user_id == $m->id){ $task = $task_1; }

        else if($task_2?->user_id == $m->id){ $task = $task_2; }

        else { $task = null; }

        
          

        if($task && $request->has("include") && is_string($request->include)){

          $task = $task->load( explode(",",$request->include) );

        }

      
        return [
          "name" => $m->first_name." ".$m->last_name,
          "id" => $res->messageable_id,
          "profilePicture" => $m->image ?? $this->getDefaultProfilePicture(),
          "task" => self::translate($task)
        ];
          
        
      });


      $total_participants = $participants->count();

        
      $myself = $participants->filter(function($res){
        if($this->myself($res["id"])){ return true; }
      })->first();



      $other = $participants->filter(function($res){
        if(!$this->myself($res["id"])){ return true; }
      })->first();


      $total_messages = $this->messages()->count();

      $messagesPerPage = $req->has("limit") ? $req->get("limit") : config("chat.messagesPerPage");


      $messages = $this->messages()->orderByDesc("id")->paginate($messagesPerPage)
        ->reverse()->values()->map(function($res){

          $user_id = $res->participation->messageable_id;
          $src = null;


          if(isset($res->data["file_url"])){ 

            $src = $res->data["file_url"];

          }

          // return to map
          return [
            "id" => $res->id,
            "content" => $res->body,
            "myself" => $this->myself($user_id),
            "participantId" => $user_id,
            "timestamp" => $res->created_at,
            "src" => $src,
            "type" => $res->type,
            "uploaded" => true,
            "viewed" => true,
      
          ];
          
        });

        
        $job = null;
        $application = null;

        if($task_1){ $job = $task_1->isJob() ? $task_1 : $task_2; }
        

        if($task_2){ $application = $task_2->isJob() ? $task_2 : $task_1; }
        
        

        $result = [
            "id" => $this->id,
            "name" => $this->name,
            "private" => $this->private,
            "total_messages" => $total_messages,
            "total_participants" => $total_participants,
            "direct_message" => $this->direct_message,
            "data" => $this->data,
            "participants" => $participants,
            "myself" => $myself,
            "other" => $other,
            "settings" => $this->data, // is converted to settings
            "tasks" => [
              "task_1" => self::translate($task_1),
              "task_2" => self::translate($task_2),
              "job" => self::translate($job),
              "application" => self::translate($application)
            ]
        ];


        if(!empty($messages)){ $result["messages"] = $messages; }
      

        return  $result;
        
  
    }


    public static function translate($model){

      return $model?->translate(request()->get("lang") ?? null);

    }



    public function myself($id){

      return request()->user()->id === $id;

    }


    public function getDefaultProfilePicture(){

      return url("Modules/Chat/img/chat-no-user.jpg");

    }


    private function getMyTask(?Tasks $task1,?Tasks $task2){

      $user_id = request()->user()->id;

      if($task1?->user_id == $user_id){ return $task1; }

      if($task2?->user_id == $user_id){ return $task2; }

      return null;

    }


    private function getOtherTask(?Tasks $task1,?Tasks $task2){

      $user_id = request()->user()->id;

      if($task1?->user_id != $user_id){ return $task1; }

      if($task2?->user_id != $user_id){ return $task2; }

      return null;

    }
    

}

/*

 
      $myUserId = $req->user()->id;


      if(!self::$header){


        $conversation = Conversation::find($this->conversation_id);
        
        $participants = $conversation->participants()->get()->map(function($o) use($myUserId){

          $m = $o->messageable;

          $myself = $myUserId === $m->id;
      
          return  [
            "id" => $m->id,
            "name" => $m->first_name." ".$m->last_name,  
            "profilePicture" => $m->image,
            "me" =>  $myself
          ];

        });


        $myself = $participants->filter(function($o){ return $o["me"] == true; })->first();

        $other = $participants->filter(function($o){ return $o["me"] != true; })->first();
        

        self::$header = $conversation->toArray() + 
        [ 
          "participants" => $participants->toArray(),

          "myself" => $myself,
            
          "other" => $other,
  
          "settings" => null,
        ];
   

      }



    

      
      return self::$header + [
        "messages" => [
          "id": $this->id,
          "content": $this->id,
          "myself": true,
          "participantId": 1,
          "timestamp": "2024-12-15T20:10:22.000000Z",
          "src": null,
          "type": "text",
          "uploaded": false,
          "viewed": true
        ]
      ];
      */