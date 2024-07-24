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
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $message = $record->formatted ?? $record->message;
        $context = $record->context;
        $levelName = $record->level_name;

        $this->teamsNotification->setColor(new LoggerColor($levelName))
            ->sendMessage($message, $context);
    }
}
