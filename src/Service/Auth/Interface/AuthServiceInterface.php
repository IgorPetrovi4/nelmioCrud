<?php
declare(strict_types=1);

namespace App\Service\Auth\Interface;

interface AuthServiceInterface
{
    /**
     * Генерирует JWT токен для пользователя при успешной аутентификации.
     *
     * @param string $email
     * @param string $password
     * @return string
     *
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function token(string $email, string $password): string;
}
