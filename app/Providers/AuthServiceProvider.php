<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

      //  Passport::loadRoutes();
    }

    /**
     * Register the application's authentication / authorization policies.
     *
     * @return void
     */
    protected function registerPolicies()
    {
        //
    }
}
