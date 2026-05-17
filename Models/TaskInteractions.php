<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class TaskInteractions extends Model{


    protected $guarded = [];

    protected $table = "tasks_interactions";

    public $translatable = ['name','slug','resume','description'];

    protected $fillable = [
        "user_id",
        "task_id",
        "likeable_task_id",
        "like",
        "should_split",
        "should_reduce"
    ];



}