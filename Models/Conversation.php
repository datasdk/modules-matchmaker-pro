<?php

namespace Modules\Tasks\Models;


use Modules\Chat\Models\Conversation as OrigConversation;


class Conversation extends OrigConversation {

   
    public function getTask1Attribute()
    {

        return  $this->data['task1'] ?? null;
      
    }


    public function getTask2Attribute()
    {

        return  $this->data['task2'] ?? null;
      
    }


    public function task1(){
 
        return $this->hasOne(Tasks::class, 'id','task1');
                    
    }


    public function task2(){
 
        return $this->hasOne(Tasks::class, 'id','task2');
                    
    }



    public function match(){

        $user_id = request()->user()->id;

        return $this->matches();

    }

    
    public function matches(){

        return $this->hasOneThrough(Tasks::class,Matches::class,"uid","id","data","match_with_task_id");

    }


   

}
