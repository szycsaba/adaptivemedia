<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Exchange\ExchangeRateClientInterface;
use App\Services\Exchange\ExchangeRateApiClient;

class ExchangeRateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ExchangeRateClientInterface::class,ExchangeRateApiClient::class);
    }

    public function boot(): void
    {
        //
    }
}