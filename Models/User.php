<?php

namespace Modules\Tasks\Models;

use App\Models\User as OrigUser;
use Modules\Tasks\Models\Tasks;
use Modules\Companies\Models\Companies;
use Modules\Teams\Support\Pivot\TeamUser;

use Multicaret\Acquaintances\Traits\CanFavorite;
use Modules\Tasks\Traits\CanBeRated;
use Modules\Tasks\Traits\CanBeFavorited;


class User extends OrigUser
{

    use CanBeRated;
    use CanFavorite;
    use CanBeFavorited;


    protected $appends = [
        'isFavorited',
    ];


    public function getMorphClass()
    {
        return OrigUser::class;
    }


    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'user_id', 'id');
    }


    /**
     * Alle virksomheder brugeren er medlem af via teams_users
     */
    public function companies()
    {

        return $this->hasManyThrough(
            Companies::class,
            TeamUser::class,
            'user_id',   // FK i pivot → users.id
            'team_id',   // FK i Companies → pivot.team_id
            'id',        // Local key på users
            'team_id'    // Local key på pivot
        );

    }


    /**
     * Én virksomhed (første aktive) via hasOneThrough
     */
    public function company()
    {

        return $this->hasOneThrough(
            Companies::class,
            TeamUser::class,
            'user_id',    // FK i pivot → users.id
            'team_id',    // FK i Companies → pivot.team_id
            'id',         // Local key på users
            'team_id'     // Local key på pivot
        )->where('companies.active', true);

    }


    public function avgRating()
    {
        return $this->averageRating();
    }

}
