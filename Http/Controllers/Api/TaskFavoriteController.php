<?php

namespace Modules\Tasks\Http\Controllers\Api;

use Orion\Http\Requests\Request;
use Modules\Reviews\Services\FavoriteService;
use Modules\Tasks\Http\Requests\FavoriteRequest;
use Modules\Reviews\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrionBaseController;
use Modules\Tasks\Models\TaskFavorites;



class TaskFavoriteController extends OrionBaseController
{

    protected $model = TaskFavorites::class;

    protected $request = FavoriteRequest::class;
    
  
    public function store(Request $req)
    {

        /** @var User $user */
        $user = $this->getUser();

        $favorite = $req->favorite ?? 1;

        $result = app(FavoriteService::class)->favorite(
            $user,
            $req->target,
            $req->target_id,
            $favorite
        );

        return response()->json($result);
    }


    public function update(Request $req, ...$args)
    {

        /** @var User $user */
        $id = $args[0];
        
        $user = $this->getUser();

        $favoriteService = app(FavoriteService::class);

        $favoriteStatus = $req->favorite ?? 1;


        if($favoriteStatus){

            $result = $favoriteService->favorite($user,$req->target,$id);

        } else {

            $result = $favoriteService->unfavorite($user,$req->target,$id);

        }
        

        return response()->json($result);

    }


    public function destroy(Request $request, ...$args)
    {

        /** @var User $user */
        $user = $this->getUser();

        $id = $args[0];

        $targetType = $request->input('target');

        $result = app(FavoriteService::class)->unfavorite(
            $user,
            $targetType,
            $id
        );

        return response()->json($result);

    }


    private function getUser()
    {
        $user = request()->user();

        return User::findOrFail($user->id);
    }
}
