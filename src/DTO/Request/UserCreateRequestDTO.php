<?php

namespace App\DTO\Request;

use App\DTO\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserCreateRequestDTO implements DtoInterface
{
    #[Assert\NotBlank(message: "Электронная почта не должна быть пустой.")]
    #[Assert\Email(message: "Укажите корректный адрес электронной почты.")]
    public string $email;

    #[Assert\NotBlank(message: "Пароль не должен быть пустым.")]
    #[Assert\Length(min: 6, max: 255, minMessage: "Пароль должен содержать минимум 6 символов.")]
    public string $password;

    #[Assert\NotBlank(message: "Имя не должно быть пустым.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Имя должно содержать от 2 до 255 символов.")]
    public string $name;

    #[Assert\NotBlank(message: "Фамилия не должна быть пустой.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Фамилия должна содержать от 2 до 255 символов.")]
    public string $surname;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserCreateRequestDTO
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserCreateRequestDTO
    {
        $this->password = $password;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): UserCreateRequestDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): UserCreateRequestDTO
    {
        $this->surname = $surname;
        return $this;
    }


}
