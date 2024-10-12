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
        // Получаем данные из запроса
        $data = json_decode($request->getContent(), true);

        // Логируем данные для отладки
        $this->logger->info('Webhook data received', $data);

        // Проверяем, что у нас есть нужные данные, например chat_id и текст сообщения
        if (isset($data['message']['chat']['id'], $data['message']['text'])) {
            $chatId = $data['message']['chat']['id'];
            $text = $data['message']['text'];

            // Обрабатываем команду /start
            if ($text === '/start') {
                // Устанавливаем глобальную кнопку Web App в меню бота
                $webAppUrl = 'https://endpointtools.com/webapp';
                $this->telegramClient->setWebAppMenuButton($webAppUrl);

                // Отправляем приветственное сообщение с кнопкой Web App
                $keyboard = [
                    [
                        [
                            'text' => 'Открыть приложение',
                            'web_app' => ['url' => $webAppUrl]
                        ]
                    ]
                ];

                $this->telegramClient->sendMessage(
                    $chatId,
                    'Добро пожаловать! Нажмите на кнопку ниже для запуска приложения:',
                    $keyboard
                );

                return new JsonResponse(['status' => 'message sent']);
            }
        }

        // Возвращаем успешный ответ для Telegram
        return new JsonResponse(['status' => 'success']);
    }
}
