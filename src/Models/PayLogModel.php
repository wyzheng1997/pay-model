<?php

namespace Ugly\Pay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ugly\Pay\Casts\Amount;
use Ugly\Pay\Enums\PayLogStatus;
use Ugly\Pay\Enums\PayLogType;

class PayLogModel extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => Amount::class,
        'type' => PayLogType::class,
        'status' => PayLogStatus::class,
        'attach' => 'json',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('ugly.pay.table') ?? parent::getTable();
    }

    /**
     * 生成单号.
     */
    public static function generateNo(): string
    {
        return config('ugly.pay.no_prefix', 'NO')
            .date('ymdHis')
            .substr(explode(' ', microtime())[0], 2)
            .Str::random(5);
    }
}
