<?php

namespace Ugly\Pay\Supports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ugly\Pay\Enums\PayStatus;
use Ugly\Pay\Enums\PayType;
use Ugly\Pay\Models\PayModel;

class CreatePayment
{
    /**
     * 支付渠道.
     */
    protected string $channel;

    /**
     * 金额.
     */
    protected float $amount;

    /**
     * 内部订单号.
     */
    protected string $orderNo;

    /**
     * 附加信息.
     */
    protected array $attach = [];

    /**
     * 回调任务.
     */
    protected ?string $job = null;

    /**
     * 付款人.
     */
    protected mixed $payer = null;

    /**
     * 收款人.
     */
    protected mixed $receiver = null;

    /**
     * 类型.
     */
    protected ?PayType $type = null;

    /**
     * 过期时间.
     */
    protected ?Carbon $expiredAt = null;

    public function setExpiredAt(Carbon $expiredAt): static
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function setChannel(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function setPayer(mixed $payer): static
    {
        $this->payer = $payer;

        return $this;
    }

    public function setReceiver(mixed $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function setOrderNo(string $orderNo): static
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    public function setAttach(array $attach): static
    {
        $this->attach = $attach;

        return $this;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }

    protected function create()
    {
        // 生成支付单号
        $no = config('ugly.pay.no_prefix', 'NO')
            .date('ymdHis')
            .substr(explode(' ', microtime())[0], 2)
            .Str::random(5);

        return PayModel::query()->create([
            'no' => $no,
            'order_no' => $this->orderNo,
            'channel' => $this->channel,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => PayStatus::PROCESSING,
            'expired_at' => $this->expiredAt ?: now()->addSeconds(config('ugly.pay.expired_at')),
            'job' => $this->job,
            'attach' => $this->attach,
            'payer_id' => $this->payer?->getKey(),
            'payer_type' => $this->payer?->getMorphClass(),
            'receiver_id' => $this->receiver?->getKey(),
            'receiver_type' => $this->receiver?->getMorphClass(),
        ]);
    }

    /**
     * 执行交易，调用支付渠道的对应方法.
     */
    public function execute(array $data = []): mixed
    {
        return DB::transaction(function () use ($data) {
            $model = $this->create();
            $method = strtolower($this->type->name);
            $channelInstance = new $model->channel;

            return $channelInstance->$method($model, $data);
        });
    }
}
