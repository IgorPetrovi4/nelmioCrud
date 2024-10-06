<?php
declare(strict_types=1);

namespace App\Controller\Telegram;


use App\ApiClient\Interface\TelegramClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class TelegramController extends AbstractController
{

    public function __construct(
        private readonly TelegramClientInterface $telegramClient
    ) {}

    #[Route('/send-webapp-link', name: 'send_webapp_link', methods: ['GET'])]
    public function sendWebAppLink(): JsonResponse
    {
        $chatId = 5485716556; // Ваш chat_id
        $webAppUrl = 'https://endpointtools.com/webapp'; // Ссылка на маршрут Symfony

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
