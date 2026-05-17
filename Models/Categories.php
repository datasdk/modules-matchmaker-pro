<?php 

namespace Modules\Tasks\Models;

use DataSDK\Categories\Models\Categories as BaseCategories;

use Modules\Tasks\Traits\HasCounter;


class Categories extends BaseCategories {
    

    use HasCounter;


    protected static function boot()
    {

        parent::boot();

        static::saving(function ($category) {
            
            if($task->hasCounter('usage')){
                
                $category->incrementCounter('usage');

            }

        });

    }

}


class Category extends Categories {

}

