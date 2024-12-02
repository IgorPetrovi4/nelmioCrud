<?php

namespace App\DTO\Response;

use App\DTO\DtoInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class UserResponseDTO implements DtoInterface
{
    #[Groups(["list"])]
    public int $id;

    #[OA\Property(description: 'The email identifier of the user.')]
    #[OA\Property(type: 'email', maxLength: 180)]
    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Email(groups: ["default", "create", "update"])]
    #[Groups(["list"])]
    public string $email;

    #[OA\Property(description: 'The name property of the user.')]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Length(min: 2, max: 255, groups: ["default", "create", "update"])]
    #[Groups(["list"])]
    public string $name;

    #[OA\Property(description: 'The surname property of the user.')]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\NotBlank(groups: ["create"])]
    #[Assert\Length(min: 2, max: 255, groups: ["default", "create", "update"])]
    #[Groups(["list"])]
    public string $surname;

    #[Groups("list")]
    public ?string $employmentDate = null;

    #[Groups(["list"])]
    public ?string $totalSalary = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): UserResponseDTO
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserResponseDTO
    {
        $this->email = $email;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): UserResponseDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): UserResponseDTO
    {
        $this->surname = $surname;
        return $this;
    }

    public function getEmploymentDate(): ?string
    {
        return $this->employmentDate;
    }

    public function setEmploymentDate(?string $employmentDate): UserResponseDTO
    {
        $this->employmentDate = $employmentDate;
        return $this;
    }

    public function getTotalSalary(): ?string
    {
        return $this->totalSalary;
    }

    public function setTotalSalary(?string $totalSalary): UserResponseDTO
    {
        $this->totalSalary = $totalSalary;
        return $this;
    }


}
