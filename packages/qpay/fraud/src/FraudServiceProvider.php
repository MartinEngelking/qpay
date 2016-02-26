<?php

namespace QPay\Fraud;
use Illuminate\Support\ServiceProvider;
use QPay\Core\Transaction;

class FraudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::creating(function($transaction) {
            $fraud_engine = $this->app->make('fraud-engine');
            $fraud_engine->check($transaction);
            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fraud-engine', function ($app) {
            return new Engine();
        });
    }
}
