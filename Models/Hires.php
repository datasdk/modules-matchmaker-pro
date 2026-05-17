<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Models\User;
use DataSDK\Available\Traits\Available;
use Modules\Tasks\Models\Matches;
use Modules\Tasks\Contracts\HiresInterface;


class Hires extends Model implements HiresInterface{

    use Available;

    protected $guarded = [];

    protected $table = "tasks_hires";
    
    public $translatable = [];

    public $appends = [
        "dates",
        "months",
        "years"
    ];

    
    protected $fillable = [
        "task_id",
        "hired_task_id"
    ];


    
    public function getDatesAttribute(){

        return $this->created_at->diffInDays(); 

    }


    public function getMonthsAttribute(){

        return $this->created_at->diffInMonths(); 

    }

    public function getYearsAttribute(){

        return $this->created_at->diffInYears(); 

    }


    public function task(){

        return $this->belongsTo(Tasks::class,"task_id");

    }


    public function user(){

        return $this->task()->user();
    }




}