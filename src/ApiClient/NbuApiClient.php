<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interface\NbuApiClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbuApiClient implements NbuApiClientInterface
{
    const URL_NBU_EXCHANGE_API = 'NBUStatService/v1/statdirectory/exchange';
    public function __construct(
        private HttpClientInterface $client,
        private string $baseUrl
    ){ }

    public function getExchangeRates(): array
    {
        $response = $this->client->request('GET', $this->baseUrl . self::URL_NBU_EXCHANGE_API.'?json');
        return $response->toArray();
    }

}