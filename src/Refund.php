<?php

namespace Ugly\Pay;

use Ugly\Pay\Enums\PayType;
use Ugly\Pay\Models\PayModel;
use Ugly\Pay\Supports\CreatePayment;

/**
 * 退款.
 */
class Refund extends CreatePayment
{
    protected ?PayType $type = PayType::REFUND;

    protected ?PayModel $receiveOrder = null;

    public function setReceiveOrder(int|PayModel $payModelOrId): static
    {
        if (is_int($payModelOrId)) {
            $payModelOrId = PayModel::query()->findOrFail($payModelOrId);
        }
        $this->receiveOrder = $payModelOrId;

        return $this;
    }

    protected function create()
    {
        // 退款单和收款单的渠道保持一致
        $this->setChannel($this->receiveOrder->channel);

        // 创建退款单并关联收款单
        $model = parent::create();
        $model->receiveOrder()->associate($this->receiveOrder);
        $model->save();

        return $model;
    }
}
