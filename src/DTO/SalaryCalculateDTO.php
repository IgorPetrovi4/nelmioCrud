<?php
declare(strict_types=1);


namespace App\DTO;

use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

class SalaryCalculateDTO
{
    #[Groups("salary_calculate")]
    private User $user;

    #[Groups("salary_calculate")]
    private ?string $averageSalary = null;

    #[Groups("salary_calculate")]
    private ?string $salaryIncrease = null;

    #[Groups("salary_calculate")]
    private ?string $exchangeRate = null;

    #[Groups("salary_calculate")]
    private ?string $currency = 'USD';


    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAverageSalary(): ?string
    {
        return $this->averageSalary;
    }

    public function setAverageSalary(?string $averageSalary): static
    {

        if ($this->exchangeRate !== null) {
            $averageSalary = bcdiv($averageSalary, $this->exchangeRate, 2);
        }

        $this->averageSalary = $averageSalary;

        return $this;
    }

    public function getSalaryIncrease(): ?string
    {
        return $this->salaryIncrease;
    }

    public function setSalaryIncrease(?string $salaryIncrease): static
    {

        if ($this->exchangeRate !== null) {
            $salaryIncrease = bcdiv($salaryIncrease, $this->exchangeRate, 2);
        }

        $this->salaryIncrease = $salaryIncrease;

        return $this;
    }

    public function getExchangeRate(): ?string
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(?string $exchangeRate): static
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }


}