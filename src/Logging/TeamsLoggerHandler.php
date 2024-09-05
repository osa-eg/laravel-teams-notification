<?php

namespace Osama\LaravelTeamsNotification\Logging;

use App\Models\User;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Osama\LaravelTeamsNotification\TeamsNotification;

class TeamsLoggerHandler extends AbstractProcessingHandler
{

    public $url;

    protected $teamsNotification;

    public function __construct($url, $level = Logger::DEBUG)
    {
        parent::__construct($level);
        $this->teamsNotification = new TeamsNotification($url);
    }

    /**
     * @param array $record
     *
     * @return array|void
     */
    protected function getMessage(array $record)
    {
        return $this->getStringLoggerMessage($record);
    }

    /**
     * @param array|LogRecord $record
     * @return void
     */
    protected function write($record): void
    {

        $data = is_array($record)? $record : $record->toArray();

        $message = $data['message'];
        $context = $data['context'];
        $levelName = $data['level_name'];

        $this->teamsNotification->setColor(new LoggerColor($levelName))
            ->sendMessage($message, $context);
    }
}
