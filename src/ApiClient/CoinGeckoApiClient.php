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

    public function getBtcToUsd(): float
    {
        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'ids' => 'bitcoin',
                'vs_currencies' => 'usd',
            ]
        ]);

        $data = $response->toArray();

        return $data['bitcoin']['usd'] ?? 0.0;
    }
}
