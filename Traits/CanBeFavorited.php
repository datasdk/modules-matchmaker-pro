<?php

namespace Modules\Tasks\Traits;

use Multicaret\Acquaintances\Traits\CanBeFavorited as BaseCanBeFavorited;

trait CanBeFavorited
{
    
    use BaseCanBeFavorited;
        
    
    public function getIsFavoritedAttribute(){

        $user = request()->user();

        return $this->isFavoritedBy($user);

    }

}
