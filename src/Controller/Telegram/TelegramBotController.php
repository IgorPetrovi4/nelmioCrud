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
    private bool $menuButton;

    public function __construct(
        private readonly TelegramClientInterface $telegramClient,
        private readonly LoggerInterface $logger,
        bool $menuButton // Injected via services.yaml
    ) {
        $this->menuButton = $menuButton;
    }

    #[Route('/telegram/webhook', name: 'telegram_webhook', methods: ['POST'])]
    public function webhook(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->logger->info('Webhook data', $data);

        $chatId = $data['message']['chat']['id'] ?? null;

        if ($chatId !== null) {
            if ($this->menuButton) {
                $this->renderChat($request);
            } else {
                // Используем метод setMenuButton из клиента
                try {
                    $this->telegramClient->setMenuButton($chatId);
                } catch (\Exception $e) {
                    $this->logger->error('Error setting menu button', ['exception' => $e]);
                }
            }
        } else {
            $this->logger->error('Chat ID not found in request data', $data);
        }

        // Возвращаем успешный ответ для Telegram
        return new JsonResponse(['status' => 'success']);
    }

    private function renderChat(Request $request): void
    {
        $data = json_decode($request->getContent(), true);
        $this->logger->info('Webhook data', $data);

        // Проверяем, что получаем команду /start
        if (isset($data['message']['text']) && $data['message']['text'] === '/start') {
            $chatId = $data['message']['chat']['id'];

            $this->logger->info('Processing /start command', ['chat_id' => $chatId]);
            $webAppUrl = 'https://endpointtools.com/webapp';

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
            try {
                $this->telegramClient->sendMessage(
                    $chatId,
                    'Добро пожаловать! Это калькулятор валют, основанный на данных Национального банка. Нажмите на кнопку для расчета:',
                    $keyboard
                );
            } catch (\Exception $e) {
                $this->logger->error('Error sending message', ['exception' => $e]);
            }
        }
    }
}
