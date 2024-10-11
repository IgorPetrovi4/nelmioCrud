<?php
declare(strict_types=1);

namespace App\Controller\Telegram;


use App\ApiClient\Interface\TelegramClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class TelegramBotController extends AbstractController
{
    public function __construct(
        private readonly TelegramClientInterface $telegramClient,
        private readonly LoggerInterface $logger
    ) {}

    #[Route('/telegram/webhook', name: 'telegram_webhook', methods: ['POST'])]
    public function webhook(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->logger->info('Webhook data', $data);

        // Проверяем, что получаем команду /start
        if (isset($data['message']['text']) && $data['message']['text'] === '/start') {
            $chatId = $data['message']['chat']['id'];

            // Запрашиваем API, если нужно (например, для получения информации)
            // Можно добавить логику запроса к вашему API тут
            $this->logger->info('Request to API', ['chat_id' => $chatId]);
            $webAppUrl = 'https://endpointtools.com/webapp'; // Ваш публичный URL Web App

            // Формируем клавиатуру с Web App кнопкой
            $keyboard = [
                [
                    [
                        'text' => 'Открыть валютный калькулятор',
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ]
            ];

            // Отправляем сообщение с кнопкой Web App пользователю
            $this->telegramClient->sendMessage($chatId, 'Добро пожаловать! Это калькулятор валют, основанный на данных Национального банка. Нажмите на кнопку для расчета:', $keyboard);
        }

        // Возвращаем успешный ответ для Telegram
        return new JsonResponse(['status' => 'success']);
    }
}
