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

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

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
        $this->publishMigrations();
        // $this->registerRoutes();
        // $this->publishStubs();
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

    protected function publishMigrations()
    {
    $timestamp = date('Y_m_d_His', time());
        $this->publishes([
            __DIR__ . '/../database/migrations/create_paymentables_tables.php.stub' => database_path() . "/migrations/{$timestamp}_create_paymentables_table.php",
        ], 'paymentable-migrations');
    }

    // private function registerRoutes()
    // {
    //     Route::group($this->routeConfiguration(), function () {
    //         $this->loadRoutesFrom(__DIR__ . '/../../routes/paymentable.php', 'paymentable-routes');
    //     });
    // }

    // private function routeConfiguration()
    // {
    //     return [
    //         'prefix' => config('paymentable.routes.prefix'),
    //         'middleware' => config('paymentable.routes.middleware'),
    //         'as' => 'paymentable.'
    //     ];
    // }


    // protected function registerMiddleware(Kernel $kernel, Router $router)
    // {
    //     // global
    //     $kernel->pushMiddleware(CapitalizeTitle::class);

    //     // route middleware
    //     // $router = $this->app->make(Router::class);
    //     $router->aliasMiddleware('capitalize', CapitalizeTitle::class);

    //     // group
    //     $router->pushMiddlewareToGroup('web', CapitalizeTitle::class);
    // }

}
