<?php
declare(strict_types=1);


namespace App\Service\SalaryCalculate;

use App\Entity\User;
use App\Service\SalaryCalculate\Interface\SalaryCalculatorInterface;

class AverageSalaryCalculator implements SalaryCalculatorInterface
{

    public function calculate(User $user, ?string $parameter): string
    {
        $salaries = $user->getSalaries();
        $numSalaries = count($salaries);

        if ($numSalaries === 0) {
            return '0';
        }

        $totalSalary = $user->getTotalSalary();

        if ($totalSalary === null) {
            return '0';
        }

        return bcdiv($totalSalary, (string)$numSalaries, 2);
    }
}