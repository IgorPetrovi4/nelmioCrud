<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "DTO for currency conversion request",
    required: ["fromCurrency", "toCurrency", "amount"]
)]
class CurrencyConvertRequest
{
    public const CURRENCY_CHOICES = ["USD", "EUR", "GBP", "CAD", "AUD", "UAH"];
    #[OA\Property(
        description: "The currency to convert from",
        example: "USD"
    )]
    #[Assert\NotBlank(message: "From currency should not be blank.")]
    #[Assert\Choice(choices: self::CURRENCY_CHOICES, message: "Invalid from currency.")]
    private string $fromCurrency;

    #[OA\Property(
        description: "The currency to convert to",
        example: "UAH"
    )]
    #[Assert\NotBlank(message: "To currency should not be blank.")]
    #[Assert\Choice(choices: self::CURRENCY_CHOICES, message: "Invalid to currency.")]
    private string $toCurrency;

    #[OA\Property(
        description: "The amount to convert",
        example: 100
    )]
    #[Assert\NotBlank(message: "Amount should not be blank.")]
    #[Assert\GreaterThan(value: 0, message: "Amount must be greater than zero.")]
    private float $amount;

    public function getFromCurrency(): string
    {
        return $this->fromCurrency;
    }

    public function setFromCurrency(string $fromCurrency): CurrencyConvertRequest
    {
        $this->fromCurrency = $fromCurrency;
        return $this;
    }

    public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    public function setToCurrency(string $toCurrency): CurrencyConvertRequest
    {
        $this->toCurrency = $toCurrency;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): CurrencyConvertRequest
    {
        $this->amount = $amount;
        return $this;
    }



}
