<?php

namespace Osama\LaravelTeamsNotification\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger as MonologLogger;
use Monolog\LogRecord;
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
     * Handle log record or array.
     *
     * @param mixed $record
     * @return void
     */
    protected function write($record)
    {
        // Check if record is an instance of LogRecord
        if ($record instanceof LogRecord) {
            $message = $record->formatted ?? $record->message;
            $context = $record->context;
            $levelName = $record->level_name;
        } else {
            // Fallback for older versions using array
            $message = $record['formatted'] ?? $record['message'];
            $context = $record['context'];
            $levelName = $record['level_name'];
        }

        // Send the message to Teams
        $this->teamsNotification->setColor(new LoggerColor($levelName))
            ->sendMessage($message, $context);
    }
}
