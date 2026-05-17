<?php

namespace Modules\Tasks\Models;

use Modules\Tasks\Models\TaskRatingsAvg;

use Modules\Tasks\Traits\CanBeRated;

use Modules\Proff\Models\ProffCompany;

use Modules\Companies\Models\Companies as OrigCompanies;
use Modules\Tasks\Traits\CanBeFavorited;


class Companies extends OrigCompanies
{

    use CanBeRated;
    use CanBeFavorited;
    
    /**
     * Tilføj et appended attribute, som returnerer rating-værdien.
     */
    protected $appends = [
        'average_rating',
        'isFavorited'
    ];

    /**
     * Skjul relationen 'avg' fra JSON-output.
     */
    protected $hidden = ['avg'];


    public function getMorphClass()
    {
        return OrigCompanies::class;
    }


    public function proff(){

       return $this->hasOne(ProffCompany::class, 'company_id'); 

    }
    


}
