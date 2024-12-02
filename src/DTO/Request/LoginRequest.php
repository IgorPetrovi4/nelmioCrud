<?php
declare(strict_types=1);

namespace App\DTO\Request;

use App\DTO\DtoInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    description: "Учетные данные пользователя для входа",
    required: ["email", "password"]
)]
class LoginRequest implements DtoInterface
{
    #[OA\Property(
        description: "Электронная почта пользователя",
        example: "mail1@example.com"
    )]
    #[Assert\NotBlank(message: "Электронная почта не должна быть пустой.")]
    #[Assert\Email(message: "Укажите корректный адрес электронной почты.")]
    private string $email;

    #[OA\Property(
        description: "Пароль пользователя",
        example: "XCrudUserPassword123!"
    )]
    #[Assert\NotBlank(message: "Пароль не должен быть пустым.")]
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}