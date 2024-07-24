<?php

namespace Osama\LaravelTeamsNotification;

use Illuminate\Support\ServiceProvider;

class LaravelTeamsNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__.'/../config/teams.php' => config_path('teams.php'),
        ], 'config');
    }

    public function register()
    {
        // Merge config file
        $this->mergeConfigFrom(
            __DIR__.'/../config/teams.php', 'teams'
        );
    }
}
