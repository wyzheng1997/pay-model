<?php

namespace Ugly\Pay;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // 设置默认配置项
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'ugly');
    }

    public function boot(): void
    {
        if ($this->app->isLocal() && $this->app->runningInConsole()) {
            // 发布迁移文件。
            $this->publishes([
                __DIR__.'/../database/migrations/create_payments_table.php' => database_path('migrations/'.date('Y_m_d_His').'_create_pay_logs_table.php'),
            ]);
        }
    }
}
