<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayLogType;
use Ugly\Pay\Supports\PayLog;

/**
 * 收款.
 */
class Receive extends PayLog
{
    protected ?PayLogType $type = PayLogType::RECEIVE;
}
