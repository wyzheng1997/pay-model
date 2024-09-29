<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayType;
use Ugly\Pay\Supports\CreatePayment;

/**
 * 收款.
 */
class Receive extends CreatePayment
{
    protected ?PayType $type = PayType::RECEIVE;
}
