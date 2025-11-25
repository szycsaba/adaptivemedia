<?php

namespace App\Services\Exchange;

interface ExchangeRateClientInterface
{
    public function convertHufToEur(int $amountHuf): float;
}
