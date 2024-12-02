<?php
declare(strict_types=1);

namespace App\ApiClient\Interface;


interface TelegramClientInterface
{
    public function sendMessage(int $chatId, string $message, array $keyboard = []): array;
    public function setWebAppMenuButton(string $webAppUrl): array;
}
