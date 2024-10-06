<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interface\TelegramClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class TelegramClient implements TelegramClientInterface
{

    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private  string $botToken
    ){ }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function sendMessage(int $chatId, string $message, array $keyboard = []): array
    {
        $url = sprintf('https://api.telegram.org/bot%s/sendMessage', $this->botToken);
        $this->logger->info('Request to API', ['url' => $url]);
        $response = $this->client->request('POST', $url, [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
                'reply_markup' => [
                    'inline_keyboard' => $keyboard
                ]
            ]
        ]);

        return $response->toArray();
    }
}
