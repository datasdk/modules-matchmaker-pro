<?php

namespace Modules\Tasks\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Tasks\Models\User;
//use Laravel\Jetstream\Features;

class UserFactory extends \Database\Factories\UserFactory
{
  
    protected $model = User::class;

}
