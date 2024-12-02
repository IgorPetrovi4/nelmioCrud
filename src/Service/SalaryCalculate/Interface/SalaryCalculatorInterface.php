<?php
declare(strict_types=1);

namespace App\Service\SalaryCalculate\Interface;

use App\Entity\User;

interface SalaryCalculatorInterface
{
    public function calculate(User $user, ?string $parameter): string;

}