<?php

namespace Osama\LaravelTeamsNotification;

use Illuminate\Support\ServiceProvider;

class LaravelTeamsNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the package's configuration file with the application's configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/teams.php', 'teams'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the package's configuration file
        $this->publishes([
            __DIR__ . '/../config/teams.php' => config_path('teams.php'),
        ], 'laravel-teams-notification-config');
    }
}
