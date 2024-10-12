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
        private readonly LoggerInterface $logger,
        private readonly  bool $menuButton
    ) {}

    #[Route('/telegram/webhook', name: 'telegram_webhook', methods: ['POST'])]
    public function webhook(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->logger->info('Webhook data', $data);

        $chatId = $data['message']['chat']['id'] ?? null;

        if ($chatId !== null) {
            if ($this->menuButton) {
                $this->renderChat($data);
            } else {
                $this->handleAutoOpen($data);
            }
        } else {
            $this->logger->error('Chat ID not found in request data', $data);
        }

        // Возвращаем успешный ответ для Telegram
        return new JsonResponse(['status' => 'success']);
    }

    private function renderChat(array $data): void
    {
        if (isset($data['message']['text']) && $data['message']['text'] === '/start') {
            $chatId = $data['message']['chat']['id'];

            $this->logger->info('Processing /start command', ['chat_id' => $chatId]);
            $webAppUrl = 'https://endpointtools.com/webapp';

            $keyboard = [
                [
                    [
                        'text' => 'Открыть валютный калькулятор',
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ]
            ];

            $this->telegramClient->sendMessage(
                $chatId,
                'Добро пожаловать! Это калькулятор валют, основанный на данных Национального банка. Нажмите на кнопку для расчета:',
                $keyboard
            );
        }
    }

    private function handleAutoOpen(array $data): void
    {
        if (isset($data['message']['text']) && strpos($data['message']['text'], '/start auto_open') === 0) {
            $chatId = $data['message']['chat']['id'];
            $webAppUrl = 'https://endpointtools.com/webapp';

            $keyboard = [
                [
                    [
                        'text' => 'Открыть валютный калькулятор',
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ]
            ];

            // Отправляем сообщение с кнопкой Web App
            $this->telegramClient->sendMessage(
                $chatId,
                'Открываем калькулятор валют...',
                $keyboard
            );

            // Дополнительно можно попытаться автоматически открыть Web App с помощью метода answerWebAppQuery
            // Но это возможно только в ответ на нажатие инлайн-кнопки, а не при входе в бота
        }
    }
}
