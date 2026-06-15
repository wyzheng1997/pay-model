<?php

namespace Ugly\Pay\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * 金额转换器. 单位分转换为元.
 */
class Amount implements CastsAttributes
{
    public function __construct(protected ?int $scale = 2) {}

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        // null 默认当成 0 分处理
        $val = $value ?? '0';
        return (float) bcdiv($val, 100, $this->scale);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        // 存入库时也兜底 null，防止计算异常
        $val = $value ?? '0';
        return (int) bcmul($val, 100, 0);
    }
}
