<?php
declare(strict_types=1);


namespace App\Service\Exchange;

use App\ApiClient\Interface\NbuApiClientInterface;
use App\Entity\User;
use App\Service\Exchange\Interface\ExchangeInterface;

class ExchangeRateService implements ExchangeInterface
{
    const URL_NBU_EXCHANGE_API = 'NBUStatService/v1/statdirectory/exchange';

    public function __construct(
        private NbuApiClientInterface $nbuApiClient
    ){ }

    public function exchangeRates(User $user, ?string $parameter): string
    {
        $data = $this->nbuApiClient->getExchangeRates(self::URL_NBU_EXCHANGE_API);

        $exchangeRate = 0;
        foreach ($data as $currencyData) {
            if ($currencyData['cc'] === $parameter) {
                $exchangeRate = $currencyData['rate'];
                break;
            }
        }

        if ($exchangeRate === 0) {
            return "Exchange rate for {$parameter} not found";
        }


       return (string) $exchangeRate;
    }
}