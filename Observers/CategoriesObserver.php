<?php

namespace Modules\Tasks\Observers;

use Modules\Tasks\Models\Categories;
use DataSDK\Categories\Models\Categories as BaseCategories;
use Illuminate\Support\Facades\Artisan;


class CategoriesObserver
{


    public function created(BaseCategories $category)
    {
        
       // Artisan::call("tasks:counters:add-missing");


        // Tilføj en "usage"-counter ved oprettelse
        if ($category->id && !$category->hasCounter("usage")) {
            $category->addCounter("usage");
        }

    }

    public function updated(BaseCategories $category)
    {

     //   Artisan::call("tasks:counters:add-missing");
        
        // Opdater counteren kun hvis nødvendigt
        if ($category->id && !$category->hasCounter("usage")) {
            $category->addCounter("usage");
        }

    }


    private function updateCategoryUsage(BaseCategories $category): void
    {
        if ($category->id && !$category->hasCounter("usage")) {
            $category->addCounter("usage");
        }
    }

 

}
