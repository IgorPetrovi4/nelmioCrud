<?php
declare(strict_types=1);

namespace App\Service\Auth;

use App\Repository\UserRepository;
use App\Service\Auth\Interface\AuthServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface    $jwtManager
    ) { }

    /**
     * {@inheritdoc}
     */
    public function token(string $email, string $password): string
    {
        // Найти пользователя по email
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user instanceof UserInterface) {
            throw new AuthenticationException('Неверные учетные данные.');
        }

        // Проверить пароль
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new AuthenticationException('Неверные учетные данные.');
        }

        // Генерировать JWT токен
        $token = $this->jwtManager->create($user);

        return $token;
    }
}
