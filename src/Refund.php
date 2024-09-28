<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayLogType;
use Ugly\Pay\Supports\PayLog;

/**
 * 退款.
 */
class Refund extends PayLog
{
    protected ?PayLogType $type = PayLogType::REFUND;
}
