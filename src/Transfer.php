<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayLogType;
use Ugly\Pay\Supports\PayLog;

/**
 * 转账.
 */
class Transfer extends PayLog
{
    protected ?PayLogType $type = PayLogType::TRANSFER;
}
