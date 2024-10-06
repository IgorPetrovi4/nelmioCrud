<?php
declare(strict_types=1);

namespace App\Service\Currency;

use App\DTO\Request\CurrencyConvertRequest;
use App\DTO\Response\CurrencyConvertResponse;

interface CurrencyConversionServiceInterface
{
    public function convert(CurrencyConvertRequest $currency): CurrencyConvertResponse;
}