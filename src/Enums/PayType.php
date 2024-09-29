<?php

namespace Ugly\Pay\Enums;

enum PayType: int
{
    // 类型: 1收款 2退款 3转账
    case RECEIVE = 1;
    case REFUND = 2;
    case TRANSFER = 3;
}
