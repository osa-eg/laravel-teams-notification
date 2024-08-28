<?php

namespace Osama\LaravelTeamsNotification\Logging;
use Monolog\Logger as MonologLogger;
use Osama\LaravelTeamsNotification\Exceptions\WebhookUrlIsNotDiscovered;
class TeamsLoggingChannel
{
    /**
     * @param array $config
     *
     * @return MonologLogger
     */
    public function __invoke(array $config)
    {
        throw_if(!isset($config['webhook_url']) && !isset($config['url']), new WebhookUrlIsNotDiscovered("Webhook URL Is Not Discovered"));
        return new TeamsLogger( $config['webhook_url']??$config['url']);
    }
}
