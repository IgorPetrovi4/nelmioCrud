<?php
declare(strict_types=1);

namespace App\Service\Currency;

use App\DTO\Request\CurrencyConvertRequest;
use App\DTO\Response\CurrencyConvertResponse;
use App\Service\Exchange\Interface\ExchangeInterface;

readonly class CurrencyConversionService implements CurrencyConversionServiceInterface
{
    public function __construct(
        private ExchangeInterface $exchange,
    ){ }

    public function convert(CurrencyConvertRequest $currency): CurrencyConvertResponse
    {
        $fromCurrency = $currency->getFromCurrency();
        $toCurrency = $currency->getToCurrency();
        $amount = $currency->getAmount();

        $exchangeRateFrom = ($fromCurrency === 'UAH') ? 1.0 : (float)$this->exchange->exchangeRates(null, $fromCurrency);
        $exchangeRateTo = ($toCurrency === 'UAH') ? 1.0 : (float)$this->exchange->exchangeRates(null, $toCurrency);

        if ($exchangeRateFrom === 0 || $exchangeRateTo === 0) {
            throw new \InvalidArgumentException('Invalid currency provided');
        }

        $exchangeRate = $exchangeRateTo / $exchangeRateFrom;
        $convertedAmount = $amount * $exchangeRate;

        return new CurrencyConvertResponse($convertedAmount);
    }
}