<?php

namespace Ugly\Pay\Supports;

use Illuminate\Support\Facades\DB;
use Ugly\Pay\Enums\PayStatus;
use Ugly\Pay\Models\PayModel;
use Ugly\Pay\Receive;
use Ugly\Pay\Refund;
use Ugly\Pay\Transfer;

class PayUtils
{
    /**
     * 退款.
     */
    public static function refund(): Refund
    {
        return new Refund;
    }

    /**
     * 转账.
     */
    public static function transfer(): Transfer
    {
        return new Transfer;
    }

    /**
     * 收款.
     */
    public static function receive(): Receive
    {
        return new Receive;
    }

    /**
     * 触发支付成功.
     */
    public static function success(string $no, array $data = []): void
    {
        DB::transaction(function () use ($no, $data) {
            $payModel = PayModel::query()
                ->lockForUpdate()
                ->where('no', $no)
                ->where('status', PayStatus::PROCESSING)
                ->first();
            if ($payModel) {
                $payModel->fill(array_merge([
                    'success_at' => now(),
                    'status' => PayStatus::SUCCESS,
                ], $data))->save();

                // 成功后执行对应的任务.
                $job = $payModel->job;
                if ($job && class_exists($job) && method_exists($job, 'dispatchSync')) {
                    call_user_func([$job, 'dispatchSync'], $payModel);
                }
            }
        });
    }
}
