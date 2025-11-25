<?php

namespace App\Services\Exchange;

interface ExchangeRateClientInterface
{
    public function convert(string $from, string $to, float $amount): float;
    public function getRatesFor(string $baseCurrency): array;
}
