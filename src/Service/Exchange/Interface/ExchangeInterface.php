<?php
declare(strict_types=1);

namespace App\Service\Exchange\Interface;

use App\Entity\User;

interface ExchangeInterface
{
    public function exchangeRates(?User $user, ?string $parameter): string;
}