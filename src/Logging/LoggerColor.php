<?php

namespace Osama\LaravelTeamsNotification\Logging;

class LoggerColor
{
    const EMERGENCY = 'attention';
    const ALERT     = 'warning';
    const CRITICAL  = 'attention';
    const ERROR     = 'attention';
    const WARNING   = 'warning';
    const NOTICE    = 'accent';
    const INFO      = 'good';
    const DEBUG     = 'default';

    /** @var string */
    private $const;

    /**
     * @param string $const
     */
    public function __construct($const = 'DEBUG')
    {
        $this->const = $const;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return constant('self::' . $this->const);
    }
}
