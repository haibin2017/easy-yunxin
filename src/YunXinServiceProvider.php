<?php

namespace Woshuo\YunXin;

use Illuminate\Support\ServiceProvider;

class YunXinServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/yunxin.php' => config_path('yunxin.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
