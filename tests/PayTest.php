<?php

namespace Ugly\Pay\Tests;

use Ugly\Pay\Models\PayModel;
use Ugly\Pay\Receive;
use Ugly\Pay\Refund;

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

        return $data;
    }

    public function transfer(PayModel $order, array $data = [])
    {
        $data['method'] = 'transfer';
        $data['receive_order_id'] = $order->receiveOrder->id;

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
        $receive = Receive::make()
            ->setChannel(TestChannel::class)
            ->setOrderNo('test_receive')
            ->setAmount(12.5)
            ->setJob('test')
            ->execute(['openid' => 'test']);

        $this->assertEquals('test', $receive['openid']);
        $this->assertEquals('receive', $receive['method']);

        // 退款
        $refund = Refund::make()
            ->setReceiveOrder($receive['id'])
            ->setOrderNo('test_refund')
            ->setAmount(12.5)
            ->execute(['desc' => '测试退款']);

        $this->assertEquals('测试退款', $refund['desc']);
        $this->assertEquals('refund', $refund['method']);
        $this->assertEquals('receive_order_id', $receive['id']);
    }
}
