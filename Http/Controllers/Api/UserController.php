<?php

namespace Modules\Tasks\Http\Controllers\Api;
use Modules\Tasks\Models\User;


class UserController extends \Modules\Crm\Http\Controllers\Api\UserController
{

    protected $model = User::class;
  

}
