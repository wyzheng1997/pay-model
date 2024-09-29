<?php

namespace Ugly\Pay\Tests;

use Ugly\Pay\Models\PayModel;
use Ugly\Pay\Supports\PayUtils;

class TestChannel
{
    public function receive(PayModel $order, array $data = [])
    {
        $data['method'] = 'receive';
        $data['id'] = $order->getKey();

        return $data;
    }

    public function refund(PayModel $order, array $data = [])
    {
        $data['method'] = 'refund';
        $data['receive_order_id'] = $order->receiveOrder->id;

        return $data;
    }

    public function transfer(PayModel $order, array $data = [])
    {
        $data['method'] = 'transfer';

        return $data;
    }
}

class PayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_receive_and_refund()
    {
        // 收款
        $receive = PayUtils::receive()
            ->setChannel(TestChannel::class)
            ->setOrderNo('test_receive')
            ->setAmount(12.5)
            ->setJob('test')
            ->execute(['openid' => 'test']);

        $this->assertEquals('test', $receive['openid']);
        $this->assertEquals('receive', $receive['method']);

        // 退款
        $refund = PayUtils::refund()
            ->setReceiveOrder($receive['id'])
            ->setOrderNo('test_refund')
            ->setAmount(12.5)
            ->execute(['desc' => '测试退款']);

        $this->assertEquals('测试退款', $refund['desc']);
        $this->assertEquals('refund', $refund['method']);
        $this->assertEquals($refund['receive_order_id'], $receive['id']);
    }

    public function test_transfer()
    {
        $transfer = PayUtils::transfer()
            ->setChannel(TestChannel::class)
            ->setOrderNo('test_transfer')
            ->setAmount(12.5)
            ->setJob('test')
            ->execute(['openid' => 'test']);

        $this->assertEquals('test', $transfer['openid']);
        $this->assertEquals('transfer', $transfer['method']);
    }
}
