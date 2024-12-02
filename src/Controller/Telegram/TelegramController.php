<?php
declare(strict_types=1);

namespace App\Controller\Telegram;


use App\ApiClient\Interface\TelegramClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class TelegramController extends AbstractController
{

    public function __construct(
        private readonly TelegramClientInterface $telegramClient,
        private LoggerInterface $logger
    ) {}

    #[Route('/send-webapp-link', name: 'send_webapp_link', methods: ['GET'])]
    public function sendWebAppLink(): JsonResponse
    {
        $chatId = 5485716556;
        $webAppUrl = 'https://endpointtools.com/webapp';
        $this->logger->info('Request to API', ['chat_id' => $chatId]);
        $this->logger->info('WebApp URL', ['url' => $webAppUrl]);
        $keyboard = [
            [
                [
                    'text' => 'Открыть калькулятор зарплаты',
                    'web_app' => ['url' => $webAppUrl]
                ]
            ]
        ];

        $this->telegramClient->sendMessage($chatId, 'Нажмите на кнопку для расчета зарплаты:', $keyboard);

        return new JsonResponse(['status' => 'success']);
    }
}
