<?php
declare(strict_types=1);

namespace App\Service\Exchange;

use App\ApiClient\Interface\NbuApiClientInterface;
use App\Entity\User;
use App\Service\Exchange\Interface\ExchangeInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRateService implements ExchangeInterface
{
    const URL_NBU_EXCHANGE_API = 'NBUStatService/v1/statdirectory/exchange';
    const CACHE_TTL = 14400; // 4 hours in seconds

    public function __construct(
        private NbuApiClientInterface $nbuApiClient,
        private CacheInterface $cache
    ) { }

    public function exchangeRates(?User $user, ?string $parameter): string
    {
        $cacheKey = 'exchange_rates_' . $parameter;

        $data = $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_TTL);
            return $this->nbuApiClient->getExchangeRates(self::URL_NBU_EXCHANGE_API);
        });

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