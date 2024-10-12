<?php

declare(strict_types=1);

namespace App\Controller\Telegram;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuickConvertBotController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/webhook/newapp", name="bot_newapp")
     */
    public function newApp(Request $request): JsonResponse
    {
        $botToken = '7547890682:AAFgV3rs6LkvHqeFMcXYMSNp5SGMD6A917A';
        // Получение JSON данных из запроса Telegram
        $data = json_decode($request->getContent(), true);

        // Извлечение chat_id из данных
        $chatId = $data['message']['chat']['id'];

        // Ссылка на ваше приложение
        $appUrl = 'https://endpointtools.com/webapp';

        // Сообщение, которое вы отправите пользователю
        $message = [
            'chat_id' => $chatId,
            'text' => "Нажмите на ссылку, чтобы открыть ваше приложение: $appUrl"
        ];

        // Отправка сообщения через Telegram API
        $response = $this->httpClient->request(
            'POST',
            'https://api.telegram.org/bot' . $botToken . '/sendMessage',
            ['json' => $message]
        );

        return new JsonResponse($response->getContent(), $response->getStatusCode());
    }
}

