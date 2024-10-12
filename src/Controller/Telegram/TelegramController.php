<?php
declare(strict_types=1);

namespace App\Controller\Telegram;


use App\ApiClient\Interface\TelegramClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class TelegramController extends AbstractController
{

    public function __construct(
        private readonly TelegramClientInterface $telegramClient,
        private LoggerInterface $logger
    ) {}

    #[Route('/send-webapp-link', name: 'send_webapp_link', methods: ['GET'])]
    public function sendWebAppLink(): RedirectResponse
    {

        $webAppUrl = 'https://endpointtools.com/webapp';
        $botUsername = '@myTestAppWebIhor_bot';
        // Create a deep link URL
        $deepLinkUrl = sprintf('https://t.me/%s?start=%s', $botUsername, urlencode($webAppUrl));

        // Redirect to the deep link URL
        return $this->redirect($deepLinkUrl);
//        $chatId = 5485716556; // Ваш chat_id
//        $this->logger->info('Request to API', ['chat_id' => $chatId]);
//        $this->logger->info('WebApp URL', ['url' => $webAppUrl]);
//        $keyboard = [
//            [
//                [
//                    'text' => 'Открыть калькулятор зарплаты',
//                    'web_app' => ['url' => $webAppUrl]
//                ]
//            ]
//        ];
//
//        $this->telegramClient->sendMessage($chatId, 'Нажмите на кнопку для расчета зарплаты:', $keyboard);
//
//        return new JsonResponse(['status' => 'success']);
    }
}
