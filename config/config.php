<?php

return [
    /*
     | ------------------------------------------------
     | 支付相关配置。
     | ------------------------------------------------
     */
    'pay' => [
        'table' => 'pay_logs', // 支付记录表名
        'no_prefix' => 'NO', // 支付单号前缀
        'expired_at' => 300, // 订单过期时间，单位：秒
    ],
];
