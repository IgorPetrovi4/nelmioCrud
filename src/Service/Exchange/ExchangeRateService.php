<?php

declare(strict_types=1);

namespace App\Service\Exchange;

use App\ApiClient\Interface\NbuApiClientInterface;
use App\ApiClient\CoinGeckoApiClient;
use App\Entity\User;
use App\Service\Exchange\Interface\ExchangeInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRateService implements ExchangeInterface
{
    const CACHE_TTL_NBU = 14400; // 4 hours in seconds
    const CACHE_TTL_BTC = 300; // 5 minutes in seconds

    public function __construct(
        private readonly NbuApiClientInterface $nbuApiClient,
        private readonly CoinGeckoApiClient    $coinGeckoApiClient,
        private readonly CacheInterface $cache
    )
    {
    }

    public function exchangeRates(?User $user, ?string $parameter): string
    {
        $cacheKey = 'exchange_rates_' . $parameter;

        $data = $this->cache->get($cacheKey, function (ItemInterface $item){
            $item->expiresAfter(self::CACHE_TTL_NBU);
            return $this->nbuApiClient->getExchangeRates();
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

        return (string)$exchangeRate;
    }

    public function getBtcExchangeRate(string $currency): string
    {
        $cacheKey = 'btc_uah_rate_'. $currency;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($currency) {
            $item->expiresAfter(self::CACHE_TTL_BTC);
            return (string) $this->coinGeckoApiClient->getBtcToСurrency($currency);
        });
    }

}