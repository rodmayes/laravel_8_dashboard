<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('manage_users', function(User $user) {
            return $user->is_admin == 1;
        });

        Gate::define('access_logs', function(User $user) {
            return $user->is_admin == 1;
        });

        Gate::define('access_telescope', function(User $user) {
            return $user->is_admin == 1;
        });

        Gate::define('access_scheduled_tasks', function(User $user) {
            return $user->is_admin == 1;
        });

        Gate::define('access_console', function(User $user) {
            return $user->is_admin == 1;
        });
    }
}
