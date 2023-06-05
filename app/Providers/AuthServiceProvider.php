<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
 
        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }

        //
 
        ResetPassword::createUrlUsing(function ($user, string $token) {
            $webAppUrl = env('WEB_APP_URL', 'http://localhost:3000');
            return $webAppUrl.'/auth/reset-password?token='.$token.'&email='.$user->email;
        });
    }
}
