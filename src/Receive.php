<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayLogType;
use Ugly\Pay\Supports\PayUtils;

/**
 * 收款.
 */
class Receive extends PayUtils
{
    protected ?PayLogType $type = PayLogType::RECEIVE;
}
