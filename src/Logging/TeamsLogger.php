<?php
namespace Osama\LaravelTeamsNotification\Logging;

use Osama\LaravelTeamsNotification\TeamsNotification;

class TeamsLogger
{
    protected $webhookUrl;

    /**
     * Constructor is removed to allow Laravel to instantiate the class without parameters.
     */
    public function __construct()
    {
        // Retrieve the webhook URL from configuration
        $this->webhookUrl = config('logging.channels.teams.webhook_url');
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
