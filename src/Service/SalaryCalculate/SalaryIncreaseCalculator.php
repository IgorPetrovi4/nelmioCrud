<?php
declare(strict_types=1);


namespace App\Service\SalaryCalculate;

use App\Entity\User;
use App\Service\SalaryCalculate\Interface\SalaryCalculatorInterface;

class SalaryIncreaseCalculator implements SalaryCalculatorInterface
{
    public function calculate(User $user, ?string $parameter): string
    {
        $parameter = $parameter ?? "0";
        $totalSalary = $user->getTotalSalary();

        if ($totalSalary === null) {
            return "0";
        }

        $increase = bcmul($totalSalary, bcdiv($parameter, "100", 2), 2);

        return bcadd($totalSalary, $increase, 2);
    }
}