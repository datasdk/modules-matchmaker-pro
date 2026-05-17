<?php

namespace Modules\Tasks\Services;

use Modules\Tasks\Models\Tasks;
use Modules\Media\Services\MediaLibraryService;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Modules\Tasks\Services\TaskStatusCodeService as Status;


class TaskService
{
 



    public function copyTask(Tasks $task,array $params = []){

    
        $parent_id = isset($params["parent_id"]) ? $params["parent_id"] : null;

        $files = is_array($task->files) ? $task->files :  $task->files->toArray();


        $copiedTask = Tasks::create(
            collect(
                array_merge(
                    $task->withoutRelations()->toArray(),
                    $params
                )
            )->except(
                'uid',
                'children',
                'deleted_at'
            )->toArray()
        )
        ->set_user($task->user_id)
        ->set_available([
            "from" => $task->available->from, 
            "to" => $task->available->to
        ])
        ->set_company($task->company_id)
        ->setCategories($task->categories->pluck("id"))
        ->syncTags($task->tags->toArray())
        ->addFiles($files)
        ->setAddress($task->address->toArray())
        ->setContact($task->contacts->toArray());
        
        
        return $copiedTask->refresh();

    }


    public function findJob(Tasks $task,Tasks $task2){

        if($task->isJob()){ return $task; }
        
        if($task2->isJob()){ return $task2; }

        return null;

    }


    public function findApplication(Tasks $task,Tasks $task2){

        if($task->isApplication()){ return $task; }
        
        if($task2->isApplication()){ return $task2; }

        return null;

    }
    

    public function taskIsEditable(Tasks $task){

        $editableStatusCodes = [
            Status::LIVE,
            Status::ONGOING
        ];

        
        if (in_array($task->status,$editableStatusCodes)){
            
            return true;

        }

        return false;


    }
}
