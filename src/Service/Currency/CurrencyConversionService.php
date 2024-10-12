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

        // Проверяем, если одна из валют — BTC, тогда используем прямую конвертацию
        if ($fromCurrency === 'BTC') {
            $btcToCurrencyRate = (float)$this->exchange->getBtcExchangeRate($toCurrency);

            if ($btcToCurrencyRate === 0) {
                throw new \InvalidArgumentException('Invalid target currency for BTC conversion');
            }

            $convertedAmount = $amount * $btcToCurrencyRate;
            return new CurrencyConvertResponse($convertedAmount);
        }

        if ($toCurrency === 'BTC') {
            $currencyToBtcRate = 1 / (float)$this->exchange->getBtcExchangeRate($fromCurrency);

            if ($currencyToBtcRate === 0) {
                throw new \InvalidArgumentException('Invalid source currency for BTC conversion');
            }

            $convertedAmount = $amount * $currencyToBtcRate;
            return new CurrencyConvertResponse($convertedAmount);
        }

        // Получаем курс обмена для валюты "From Currency" (если UAH - курс 1.0)
        $exchangeRateFrom = ($fromCurrency === 'UAH') ? 1.0 : (float)$this->exchange->exchangeRates(null, $fromCurrency);

        // Получаем курс обмена для валюты "To Currency" (если UAH - курс 1.0)
        $exchangeRateTo = ($toCurrency === 'UAH') ? 1.0 : (float)$this->exchange->exchangeRates(null, $toCurrency);

        // Проверяем корректность получения курсов
        if ($exchangeRateFrom === 0 || $exchangeRateTo === 0) {
            throw new \InvalidArgumentException('Invalid currency provided');
        }

        // Корректируем расчет общего коэффициента обмена
        $exchangeRate = $exchangeRateFrom / $exchangeRateTo;

        // Конвертируем сумму из валюты "From Currency" в "To Currency"
        $convertedAmount = $amount * $exchangeRate;

        // Возвращаем результат с конвертированной суммой
        return new CurrencyConvertResponse($convertedAmount);
    }
}