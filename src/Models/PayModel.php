<?php

namespace Ugly\Pay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ugly\Pay\Casts\Amount;
use Ugly\Pay\Enums\PayStatus;
use Ugly\Pay\Enums\PayType;

class PayModel extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => Amount::class,
        'type' => PayType::class,
        'status' => PayStatus::class,
        'attach' => 'json',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('ugly.pay.table') ?? parent::getTable();
    }

    /**
     * 收款单.
     */
    public function receiveOrder(): BelongsTo
    {
        return $this->belongsTo(PayModel::class, 'receive_id');
    }

    /**
     * 退款单.
     */
    public function refundOrders(): HasMany
    {
        return $this->hasMany(PayModel::class, 'receive_id');
    }

    /**
     * 收款人.
     */
    public function receiver(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 付款人.
     */
    public function payer(): MorphTo
    {
        return $this->morphTo();
    }
}
