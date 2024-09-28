<?php

namespace Ugly\Pay\Enums;

enum PayLogStatus: int
{
    // 状态: 1处理中 2成功 3失败
    case PROCESSING = 1;
    case SUCCESS = 2;
    case FAIL = 3;
}
