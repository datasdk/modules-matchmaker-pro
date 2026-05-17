<?php

namespace Modules\Tasks\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Tasks\Models\Tasks;
use App\Policies\AccessPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      //  Tasks::class => ActionPolicy::class,
    ];
   
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(){
        

        $this->registerPolicies();


    }

}
