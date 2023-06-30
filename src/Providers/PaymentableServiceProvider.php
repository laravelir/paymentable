<?php

namespace Laravelir\Paymentable\Providers;

use Illuminate\Support\ServiceProvider;
use Laravelir\Paymentable\Console\Commands\InstallPackageCommand;
use Laravelir\Paymentable\Facades\Paymentable;

class PaymentableServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/../../config/paymentable.php", 'paymentable');

        $this->registerFacades();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
    }

    private function registerFacades()
    {
        $this->app->bind('paymentable', function ($app) {
            return new Paymentable();
        });
    }

    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                InstallPackageCommand::class,
            ]);
        }
    }

    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/paymentable.php' => config_path('paymentable.php')
        ], 'paymentable-config');
    }
}
