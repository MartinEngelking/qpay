<?php

namespace QPay\Web;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'web');
        $this->setupRoutes();
        $this->publishAssets();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * Sets up API routes.
     */
    public function setupRoutes()
    {
        $this->app->router->group([
            'namespace' => 'QPay\API\Http\Controllers',
            'prefix' => ''
        ], function ($router) {
            require __DIR__ . '/Http/routes.php';
        });
    }

    /**
     * Publishes assets, which in our case are the JS webapp and CSS.
     */
    public function publishAssets()
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/qpay'),
        ], 'public');
    }

}
