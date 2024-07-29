<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Authorization by using Gate, ref: https://laravel.com/docs/11.x/authorization
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('pengawas', function ($user) {
            return $user->role === 'pengawas';
        });

        Gate::define('unit', function ($user) {
            return $user->role === 'unit';
        });
    }
}
