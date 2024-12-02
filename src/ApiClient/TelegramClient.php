<?php
declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\Interface\TelegramClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class TelegramClient implements TelegramClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private string $botToken
    ) { }

    public function sendMessage(int $chatId, string $message, array $keyboard = []): array
    {
        $url = sprintf('https://api.telegram.org/bot%s/sendMessage', $this->botToken);
        $this->logger->info('Request to sendMessage API', ['url' => $url]);

        $response = $this->client->request('POST', $url, [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
                'reply_markup' => [
                    'inline_keyboard' => $keyboard
                ],
                'cache_time' => 0,
            ]
        ]);

        return $response->toArray();
    }

    public function setWebAppMenuButton(string $webAppUrl): array
    {
        $url = sprintf('https://api.telegram.org/bot%s/setChatMenuButton', $this->botToken);
        $this->logger->info('Setting Web App menu button', ['url' => $url]);

        $response = $this->client->request('POST', $url, [
            'json' => [
                'menu_button' => [
                    'type' => 'web_app',
                    'text' => 'Открыть приложение',
                    'web_app' => [
                        'url' => $webAppUrl
                    ]
                ]
            ]
        ]);

        return $response->toArray();
    }
}
