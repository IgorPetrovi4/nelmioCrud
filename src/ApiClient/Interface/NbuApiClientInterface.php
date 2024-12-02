<?php
declare(strict_types=1);

namespace App\ApiClient\Interface;

interface NbuApiClientInterface
{
    public function getExchangeRates(): array;
}