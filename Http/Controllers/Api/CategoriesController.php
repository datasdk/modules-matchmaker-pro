<?php

namespace Modules\Tasks\Http\Controllers\Api;

use DataSDK\Categories\Http\Controllers\Api\CategoriesController as BaseCategoriesController;
use Modules\Tasks\Models\Categories;
use Modules\Tasks\Http\Resources\CategoriesResource;


class CategoriesController extends BaseCategoriesController
{

    protected $model = Categories::class;

    protected $resource = CategoriesResource::class;



    protected $includes = [
        'images',
        "children",
        "counter",
        "counters"
    ];

    protected $exposedScopes = [
        'type', 
        'sortingById', 
        'descendantsAndSelf', 
        'orDescendantsAndSelf', 
        'descendantsOf', 
        'whereId', 
        'childrenAndSelf',
        'OrderByCounterValue'
    ];

    protected $sortableBy = [
        "parent_id", 
        "name", 
        "sorting", 
        "counter",
        "counter.value",
        "counters.value"
    ];

    protected $filterableBy = [
        "id", 
        "parent_id", 
        "type", 
        "categories.id",
    
    ];


}
