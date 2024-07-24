<?php

namespace Osama\LaravelTeamsNotification;

use Illuminate\Support\ServiceProvider;
use Osama\LaravelTeamsNotification\Console\Commands\PublishTests;

class LaravelTeamsNotificationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../tests' => base_path('tests/laravel-teams-notification'),
        ], 'laravel-teams-notification-tests');

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

        $this->commands([
            PublishTests::class,
        ]);
    }
}
