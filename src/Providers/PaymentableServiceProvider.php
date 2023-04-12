<?php

namespace Laravelir\Paymentable\Providers;

use App\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravelir\Paymentable\Console\Commands\InstallPackageCommand;
use Laravelir\Paymentable\Facades\Paymentable;

class PaymentableServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/../../config/paymentable.php", 'paymentable');

        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // $this->registerViews();

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
        // $this->registerTranslations();
        // $this->registerAssets();
        // $this->registerRoutes();
        // $this->registerBladeDirectives();
        // $this->publishStubs();
        // $this->registerLivewireComponents();
    }

    private function registerFacades()
    {
        $this->app->bind('paymentable', function ($app) {
            return new Paymentable();
        });
    }

    // private function registerViews()
    // {
    //     $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'paymentable');

    //     $this->publishes([
    //         __DIR__ . '/../../resources/views' => resource_path('views/laravelir/paymentable'),
    //     ]);
    // }


    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                InstallPaymentableCommand::class,
            ]);
        }
    }

    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/paymentable.php' => config_path('paymentable.php')
        ], 'paymentable-config');
    }

    // private function registerAssets()
    // {
    //     $this->publishes([
    //         __DIR__ . '/../../resources/statics' => public_path('paymentable'),
    //     ], 'paymentable-assets');
    // }

    // private function publishStubs()
    // {
    //     $this->publishes([
    //         __DIR__ . '/../Console/Stubs' => resource_path('vendor/laravelir/paymentable/stubs'),
    //     ], 'paymentable-stubs');
    // }

    // public function registerTranslations()
    // {
    //     $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'paymentable');

    //     $this->publishes([
    //         __DIR__ . '/../../resources/lang' => resource_path('lang/laravelir/paymentable'),
    //     ], 'paymentable-langs');
    // }

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

    // protected function publishMigrations()
    // {
    // $timestamp = date('Y_m_d_His', time());
    //     $this->publishes([
    //         __DIR__ . '/../database/migrations/paymentable_tables.stub' => database_path() . "/migrations/{$timestamp}paymentable_tables.php",
    //     ], 'paymentable-migrations');
    // }

    // protected function registerBladeDirectives()
    // {
    //     Blade::directive('format', function ($expression) {
    // return "<?php echo ($expression)->format('m/d/Y H:i') ?/>";
    //     });

    //     Blade::directive('config', function ($key) {
    //         return "<?php echo config('paymentable.' . $key); ?/>";
    //     });
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

    // public function registerLivewireComponents()
    // {
    // Livewire::component('test', Test::class);
    // }
}
