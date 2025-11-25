<?php

namespace App\Services\Exchange;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExchangeRateApiClient implements ExchangeRateClientInterface
{
    private string $baseUrl;
    private string $apiKey;
    private string $baseCurrency;

    public function __construct()
    {
        $this->baseUrl = config('exchange.base_url');
        $this->apiKey = config('exchange.api_key');
        $this->baseCurrency = config('exchange.base_currency', 'HUF');

        if ($this->baseUrl === '' || $this->apiKey === '' || $this->baseCurrency === '') {
            throw new RuntimeException('Exchange API configuration is missing.');
        }
    }

    public function convertHufToEur(int $amountHuf): float
    {
        $rate = $this->getEurRate();
        return round($amountHuf * $rate, 2);
    }

    private function getEurRate(): float
    {
        $cacheKey = 'eur_rate_from_' . $this->baseCurrency;

        return Cache::remember($cacheKey, now()->addHour(), function () {
            $url = $this->baseUrl . '/' . $this->apiKey . '/latest/' . $this->baseCurrency;

            $response = Http::withOptions([
                'verify' => config('exchange.ca_cert')
            ])->get($url);

            if ($response->failed()) {
                throw new RuntimeException('Failed to fetch exchange rates.');
            }

            $data = $response->json();

            if (!isset($data['result']) || $data['result'] !== 'success') {
                throw new RuntimeException('Exchange API returned an unexpected response.');
            }

            if (!isset($data['conversion_rates']['EUR'])) {
                throw new RuntimeException('EUR rate not found in API response.');
            }

            return (float) $data['conversion_rates']['EUR'];
        });
    }
}
