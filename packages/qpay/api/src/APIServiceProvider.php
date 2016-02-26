<?php

namespace QPay\API;

use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupRoutes();
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
            'prefix' => 'api'
        ], function ($router) {
            require __DIR__ . '/Http/routes.php';
        });
    }
}
