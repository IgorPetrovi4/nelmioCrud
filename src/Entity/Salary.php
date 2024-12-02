<?php

namespace App\Entity;

use App\Repository\SalaryRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalaryRepository::class)]
#[Index(name: 'idx_amount_currency_code', columns: ['amount', 'currency_code'])]
#[ORM\HasLifecycleCallbacks]
class Salary extends AbstractLatestTimestamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["list", "payment_response"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'salaries')]
    #[Groups(["payment_response"])]
    private ?User $user = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[OA\Property(description: 'The amount of the salary.')]
    #[Groups(["list", "payment", "payment_response"])]
    #[Assert\NotBlank(groups: ["payment"])]
    #[Assert\Regex(
        pattern: '/^(?!0\d)\d{3,8}(\.\d{1,2})?$/',
        message: 'The amount must be a decimal with a maximum of 10 digits in total, including up to 2 after the decimal point, and not less than 100. Use a dot (.) as the decimal separator.',
        groups: ["payment"]
    )]
    private ?string $amount = null;

    #[ORM\Column(type: "string", length: 3)]
    #[OA\Property(description: 'The currency code of the salary.')]
    #[Groups(["list", "payment", "payment_response"])]
    #[Assert\NotBlank(groups: ["payment"])]
    #[Assert\Currency(
        message: 'The currency code is not valid.',
        groups: ["payment"]
    )]
    private ?string $currencyCode = 'USD';
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["list", "payment_response", "payment"])]
    #[Assert\NotBlank(groups: ["payment"])]
    #[Assert\Type(type: "DateTimeInterface", groups: ["payment"])]
    private string|DateTimeInterface|null $paymentDate = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(?string $currencyCode): static
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function getPaymentDate(): ?string
    {
        return $this->paymentDate->format('Y-m-d');
    }

    public function setPaymentDate(?\DateTimeInterface $paymentDate): static
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }


}
