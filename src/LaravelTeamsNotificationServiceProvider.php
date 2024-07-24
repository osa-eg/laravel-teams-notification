<?php

namespace Osama\LaravelTeamsNotification;

use Illuminate\Support\ServiceProvider;
use Osama\LaravelTeamsNotification\Console\Commands\PublishTests;

class LaravelTeamsNotificationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Publishing configuration files
        $this->publishes([
            __DIR__ . '/../config/teams.php' => config_path('teams.php'),
        ], 'laravel-teams-notification-config');
    }

    public function register()
    {
        // Merge config file
        $this->mergeConfigFrom(
            __DIR__.'/../config/teams.php', 'teams'
        );

    }
}
