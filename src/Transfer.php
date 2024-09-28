<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayLogType;
use Ugly\Pay\Supports\PayUtils;

/**
 * 转账.
 */
class Transfer extends PayUtils
{
    protected ?PayLogType $type = PayLogType::TRANSFER;
}
