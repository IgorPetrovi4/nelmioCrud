<?php
declare(strict_types=1);

namespace App\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGeckoApiClient
{
    const BASE_URL = 'https://api.coingecko.com/api/v3/simple/price';

    public function __construct(
        private HttpClientInterface $client
    )
    {
    }
    public function getBtcToСurrency(string $currency): float
    {
        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'ids' => 'bitcoin',
                'vs_currencies' => $currency,
            ]
        ]);

        $data = $response->toArray();

        return $data['bitcoin'][$currency] ?? 0.0;
    }

    public function getBtcToUah(): float
    {
        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'ids' => 'bitcoin',
                'vs_currencies' => 'uah',
            ]
        ]);

        $data = $response->toArray();

        return $data['bitcoin']['uah'] ?? 0.0;
    }

}
