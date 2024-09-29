<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayType;
use Ugly\Pay\Supports\CreatePayment;

/**
 * 转账.
 */
class Transfer extends CreatePayment
{
    protected ?PayType $type = PayType::TRANSFER;
}
