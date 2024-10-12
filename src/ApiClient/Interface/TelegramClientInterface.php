<?php
declare(strict_types=1);

namespace App\ApiClient\Interface;


interface TelegramClientInterface
{
    /**
     * Отправляет сообщение через Telegram Bot API
     *
     * @param int $chatId Идентификатор чата в Telegram
     * @param string $message Сообщение для отправки
     * @param array $keyboard Клавиатура (по умолчанию пустой массив)
     * @return array Ответ от API в формате массива
     */
    public function sendMessage(int $chatId, string $message, array $keyboard = []): array;


    /**
     * Устанавливает кнопку меню для чата в Telegram
     *
     * @param int $chatId Идентификатор чата в Telegram
     * @return array Ответ от API в формате массива
     */
    public function setMenuButton(int $chatId): array;

    /**
     * Возвращает токен бота
     *
     * @return string Токен бота
     */
    public function getBotToken(): string;
}
