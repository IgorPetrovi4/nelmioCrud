<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    description: "DTO для расчёта зарплаты пользователя.",
    required: ["percentage", "currency"]
)]
class SalaryCalculateDTO implements DtoInterface
{
    public const PERCENTAGE_DESCRIPTION = "Процент увеличения зарплаты.";
    public const PERCENTAGE_EXAMPLE = 10.6;

    public const CURRENCY_DESCRIPTION = "Валюта для курсов обмена.";
    public const CURRENCY_EXAMPLE = "USD";
    public const CURRENCY_CHOICES = ["USD", "EUR", "GBP", "CAD", "AUD"];

    #[OA\Property(
        description: self::PERCENTAGE_DESCRIPTION,
        example: self::PERCENTAGE_EXAMPLE
    )]
    #[Assert\Positive(message: "Percentage must be a positive number.")]
    private string $percentage;

    #[OA\Property(
        description: self::CURRENCY_DESCRIPTION,
        example: self::CURRENCY_EXAMPLE
    )]
    #[Assert\NotBlank(message: "Currency is required.")]
    #[Assert\Choice(choices: self::CURRENCY_CHOICES, message: "Invalid currency.")]
    #[Groups("salary_calculate")]
    private string $currency;

    #[OA\Property(
        description: "Пользователь, для которого производится расчёт зарплаты.",
        example: [
            "id" => 2,
            "email" => "mail2@example.com",
            "totalSalary" => "5071.00"
        ],
        nullable: true
    )]
    #[Groups("salary_calculate")]
    private ?User $user = null;

    #[OA\Property(
        description: "Курс обмена выбранной валюты.",
        example: 1.0
    )]
    #[Groups("salary_calculate")]
    private string $exchangeRate;

    #[OA\Property(
        description: "Средняя зарплата пользователя.",
        example: 45000.0
    )]
    #[Groups("salary_calculate")]
    private string $averageSalary;

    #[OA\Property(
        description: "Увеличение зарплаты.",
        example: 5250.0
    )]
    #[Groups("salary_calculate")]
    private string $salaryIncrease;



    public function getPercentage(): string
    {
        return $this->percentage;
    }

    public function setPercentage(string $percentage): self
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getExchangeRate(): string
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(string $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;
        return $this;
    }

    public function getAverageSalary(): string
    {
        return $this->averageSalary;
    }

    public function setAverageSalary(string $averageSalary): self
    {
        $this->averageSalary = $averageSalary;
        return $this;
    }

    public function getSalaryIncrease(): string
    {
        return $this->salaryIncrease;
    }

    public function setSalaryIncrease(string $salaryIncrease): self
    {
        $this->salaryIncrease = $salaryIncrease;
        return $this;
    }
}
