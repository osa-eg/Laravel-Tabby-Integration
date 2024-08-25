<?php
namespace Osama\TabbyIntegration\Providers;

use Illuminate\Support\ServiceProvider;
use Osama\TabbyIntegration\Services\TabbyService;
use Psr\Log\LoggerInterface;
class TabbyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('tabby', function ($app) {
            return new TabbyService($app->make(LoggerInterface::class));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/tabby.php' => config_path('tabby.php'),
        ], 'config');
    }
}
