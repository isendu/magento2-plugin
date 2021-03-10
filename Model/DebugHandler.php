<?php

namespace Isendu\ConnectorModule\Model;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

class DebugHandler extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/debug_isendu.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;
}
