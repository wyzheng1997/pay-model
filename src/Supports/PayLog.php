<?php

namespace Ugly\Pay\Supports;

use Illuminate\Support\Carbon;
use Ugly\Pay\Enums\PayLogStatus;
use Ugly\Pay\Enums\PayLogType;
use Ugly\Pay\Models\PayLogModel;

class PayLog
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
     *
     * @var mixed|null
     */
    protected mixed $payer = null;

    /**
     * 收款人.
     *
     * @var mixed|null
     */
    protected mixed $receiver = null;

    /**
     * 类型.
     */
    protected ?PayLogType $type = null;

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

    protected function createPayLog()
    {
        return PayLogModel::query()->create([
            'no' => PayLogModel::generateNo(),
            'order_no' => $this->orderNo,
            'channel' => $this->channel,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => PayLogStatus::PROCESSING,
            'expired_at' => $this->expiredAt ?: now()->addSeconds(config('ugly.pay.expired_at')),
            'job' => $this->job,
            'attach' => $this->attach,
            'payer_id' => $this->payer?->getKey(),
            'payer_type' => $this->payer?->getMorphClass(),
            'receiver_id' => $this->receiver?->getKey(),
            'receiver_type' => $this->receiver?->getMorphClass(),
        ]);
    }
}
