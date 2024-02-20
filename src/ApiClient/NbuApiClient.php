<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interface\NbuApiClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbuApiClient implements NbuApiClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $baseUrl
    ){ }

    public function getExchangeRates(string $endpoint): array
    {
        $response = $this->client->request('GET', $this->baseUrl . $endpoint.'?json');
        return $response->toArray();
    }

}