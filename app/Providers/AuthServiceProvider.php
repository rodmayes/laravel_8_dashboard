<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Opcodes\LogViewer\LogFile;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('viewDatabaseSchedule', function (User $user) {
            return Gate::denies('admin');
        });

        Gate::define('downloadLogFile', function (User $user, LogFile $file) {
            return Gate::denies('admin');
        });

        Gate::define('hasRole', function (User $user, $role) {
            return $user->hasRole($role);
        });
    }
}
