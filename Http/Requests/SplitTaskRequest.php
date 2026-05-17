<?php

namespace Modules\Tasks\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\MatchService;


class SplitTaskRequest extends FormRequest
{

    public function authorize()
    {
        // Du kan tilføje adgangskontrol her, fx tjekke om brugeren ejer tasken
        return true;
    }


    public function rules()
    {


        $split_task = Tasks::findOrFail($this->split_task_id);

        $compare_task = Tasks::findOrFail($this->compare_task_id);



        if($split_task->status != "live" || $compare_task->status != "live"){

            abort(400, 'Du kan kun splitte igangværende projekter');

        }


        if($split_task->hasChildren()){
            
            abort(400, 'Du kan ikke splitte dette opslag, da det allerede er splittet');

        }


        if($split_task->id == $compare_task->id){

            abort(400, 'id for begge opslag må ikke være ens');

        }


        if (!$split_task->isApplication()) {

         //   abort(400, 'Ugyldig opgavetype: Den første opgave skal være et mandskabsopslag.');

        }

        
        if (!$compare_task->isJob()) {

         //   abort(400, 'Ugyldig opgavetype: Den anden opgave skal være et projekt');

        }

        
        if(!$this->isMatching($split_task,$compare_task)){

            abort(400, 'Disse opslag har ikke matched endnu');

        }
        


        return [
            'split_task_id' => ['required', 'exists:tasks,id'],
            'compare_task_id' => ['required', 'exists:tasks,id'],
            'should_split' => ['sometimes', 'boolean'],
            'should_reduce' => ['sometimes', 'boolean'],
        ];

    }


    public function messages()
    {

        return [
            'applicationTask.required' => 'Du skal angive en ansøgnings-opgave (applicationTask).',
            'applicationTask.exists' => 'Den valgte ansøgnings-opgave findes ikke.',

            'jobTask.required' => 'Du skal angive en job-opgave (jobTask).',
            'jobTask.exists' => 'Den valgte job-opgave findes ikke.',

            'should_split.boolean' => 'Feltet should_split skal være sandt eller falsk (true/false).',
        ];

    }


    private function isMatching(Tasks $split_task,Tasks $compare_task){

        return app(MatchService::class)->isMatching($split_task,$compare_task);

    }

}
