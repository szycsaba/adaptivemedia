<?php

namespace App\Services\Exchange;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExchangeRateApiClient implements ExchangeRateClientInterface
{
    private string $baseUrl;
    private string $apiKey;
    private string $caCert;

    public function __construct()
    {
        $this->baseUrl = config('exchange.base_url');
        $this->apiKey  = config('exchange.api_key');
        $this->caCert  = config('exchange.ca_cert');
    }


    public function convert(string $from, string $to, float $amount): float
    {
        $rates = $this->getRatesFor($from);

        if (!isset($rates[$to])) {
            throw new \RuntimeException("Cannot convert from {$from} to {$to}");
        }

        return round($amount * $rates[$to], 2);
    }

    public function getRatesFor(string $baseCurrency): array
    {
        return Cache::remember(
            "exchange_rates_{$baseCurrency}",
            now()->addHours(1),
            function () use ($baseCurrency) {

                $url = "{$this->baseUrl}/{$this->apiKey}/latest/{$baseCurrency}";

                $response = Http::withOptions([
                    'verify' => $this->caCert,
                ])->get($url);

                if (!$response->successful()) {
                    throw new \RuntimeException("Exchange API error: {$response->status()}");
                }

                $json = $response->json();

                if (!isset($json['result']) || $json['result'] !== 'success') {
                    throw new \RuntimeException("Invalid response from exchange API");
                }

                return $json['conversion_rates'] ?? [];
            }
        );
    }
}
