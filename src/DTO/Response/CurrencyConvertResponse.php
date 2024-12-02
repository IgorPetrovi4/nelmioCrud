<?php

declare(strict_types=1);

namespace App\DTO\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "DTO for currency conversion response"
)]
class CurrencyConvertResponse
{
    #[OA\Property(
        description: "The converted amount",
        example: 2750
    )]
    private float $convertedAmount;

    public function __construct(float $convertedAmount)
    {
        $this->convertedAmount = $convertedAmount;
    }

    public function getConvertedAmount(): float
    {
        return $this->convertedAmount;
    }

    public function setConvertedAmount(float $convertedAmount): CurrencyConvertResponse
    {
        $this->convertedAmount = $convertedAmount;
        return $this;
    }
}
