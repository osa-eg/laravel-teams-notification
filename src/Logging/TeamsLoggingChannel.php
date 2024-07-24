<?php

namespace Osama\LaravelTeamsNotification\Logging;

use Monolog\Logger as MonologLogger;

class TeamsLoggingChannel
{
    /**
     * @param array $config
     *
     * @return MonologLogger
     */
    public function __invoke(array $config)
    {
        return new TeamsLogger( $config['webhook_url']);
    }
}
