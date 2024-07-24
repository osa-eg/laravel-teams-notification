<?php

namespace Osama\LaravelTeamsNotification\Logging;

use Osama\LaravelTeamsNotification\TeamsNotification;

class TeamsLogger
{
    protected $webhookUrl;

    /**
     * @param $webhookUrl
     */
    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * @param array $config
     * @return TeamsLoggerHandler
     */
    public function __invoke(array $config)
    {
        return new TeamsLoggerHandler(
            new TeamsNotification($this->webhookUrl),
            $config['level'] ?? \Monolog\Logger::DEBUG
        );
    }
}
