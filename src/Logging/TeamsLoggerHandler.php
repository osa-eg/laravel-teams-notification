<?php

namespace Osama\LaravelTeamsNotification\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger as MonologLogger;
use Osama\LaravelTeamsNotification\TeamsNotification;

class TeamsLoggerHandler extends AbstractProcessingHandler
{
    protected $teamsNotification;

    public function __construct(TeamsNotification $teamsNotification, $level = MonologLogger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->teamsNotification = $teamsNotification;
    }

    /**
     * @param array $record
     * @return void
     */
    protected function write(array $record)
    {
        // Format the log message
        $message = $record['formatted'] ?? $record['message'];

        // Send the message to Teams
        $this->teamsNotification->setColor(new LoggerColor($record['level_name']))->sendMessage($message, $record['context']);
    }
}
