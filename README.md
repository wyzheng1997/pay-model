# pay-model
适用于laravel快速开发付款（收款）、退款和转账功能的工具包
> 此扩展包是把所有的支付场景都粗暴的分为收款、转账和退款三个类别。通过支付通道（channel）和任务（job）将支付款流程和业务逻辑解耦，让开发者更专注于业务本身。

# 安装
```
composer require wyzheng/pay-model

php artisan vendor:publish --provider="Ugly\Pay\ServiceProvider"
php artisan migrate
```
# 使用方法
首先需要创建一个支付通道，根据业务需要编写receive、refund、transfer方法。
```php
<?php
namespace App\Payment\Channel;
use Ugly\Pay\Models\PayModel;

class WechatPay {
    // 收款
    public function receive(PayModel $pay, array $data) {
        // 在这里向第三方支付发起支付请求
        // $data 是在调用支付的时候传递的自定义参数，比如微信支付时用到的openid等
        // return 第三方支付返回的参数，方便后续处理
        return $payjson;
    }
    
    // 退款
    public function refund(PayModel $pay, array $data) {}
    
    // 转账
    public function transfer(PayModel $pay, array $data) {}
}
```
其次需要创建一个任务，用于处理支付结果。

```php
<?php
namespace App\Payment\Job;
use Illuminate\Contracts\Queue\ShouldQueue;use Ugly\Pay\Models\PayModel;

class BuyVipJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(protected PayModel $payModel)
    {
        //
    }
    
    public function handle(): void
    {
    
    }
}
```

在需要的地方通过`\Ugly\Pay\Supports\PayUtils`的静态方法
```php
use Ugly\Pay\Supports\PayUtils;
use App\Payment\Channel\WechatPay;
use App\Payment\Job\RefundSuccessJob;
use App\Payment\Job\TransferSuccessJob;
// 收款
$receive = PayUtils::receive()
    ->setChannel(WechatPay::class) // 支付通道
    ->setOrderNo('test_receive') // 订单号
    ->setAmount(12.5) // 金额
    ->setJob(BuyVipJob::class) // 成功后需要处理任务
    ->execute(['openid' => 'test']); // 会调用支付通道的receive()方法
dd($receive); // 支付通过receive函数的返回值

// 退款
$refund = PayUtils::refund()
    ->setReceiveOrder(1) // 退款需要传入收款单的ID，同时也支持PayModel对象
    ->setOrderNo('test_receive') // 订单号
    ->setAmount(12.5) // 金额
    ->setJob(RefundSuccessJob::class) // 成功后需要处理任务
    ->execute(['openid' => 'test']); // 会调用支付通道的refund()方法
dd($refund); // 支付通过refund函数的返回值

// 转账
$transfer = PayUtils::transfer()
    ->setChannel(WechatPay::class) // 支付通道
    ->setOrderNo('test_transfer') // 订单号
    ->setAmount(12.5) // 金额
    ->setJob(TransferSuccessJob::class) // 成功后需要处理任务
    ->execute(['openid' => 'test']);
dd($transfer); // 支付通过transfer函数的返回值
```
