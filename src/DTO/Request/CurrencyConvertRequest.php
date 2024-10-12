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
    public const CURRENCY_CHOICES = ["EUR", "UAH", "USD", "RUB", "BTC"];
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
    //#[Assert\PositiveOrZero(message: "Amount must be zero or a positive number.")]
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
// array:61 [
//  0 => array:5 [
//    "r030" => 36
//    "txt" => "Австралійський долар"
//    "rate" => 27.7244
//    "cc" => "AUD"
//    "exchangedate" => "14.10.2024"
//  ]
//  1 => array:5 [
//    "r030" => 124
//    "txt" => "Канадський долар"
//    "rate" => 29.9035
//    "cc" => "CAD"
//    "exchangedate" => "14.10.2024"
//  ]
//  2 => array:5 [
//    "r030" => 156
//    "txt" => "Юань Женьміньбі"
//    "rate" => 5.828
//    "cc" => "CNY"
//    "exchangedate" => "14.10.2024"
//  ]
//  3 => array:5 [
//    "r030" => 203
//    "txt" => "Чеська крона"
//    "rate" => 1.7801
//    "cc" => "CZK"
//    "exchangedate" => "14.10.2024"
//  ]
//  4 => array:5 [
//    "r030" => 208
//    "txt" => "Данська крона"
//    "rate" => 6.0345
//    "cc" => "DKK"
//    "exchangedate" => "14.10.2024"
//  ]
//  5 => array:5 [
//    "r030" => 344
//    "txt" => "Гонконгівський долар"
//    "rate" => 5.3004
//    "cc" => "HKD"
//    "exchangedate" => "14.10.2024"
//  ]
//  6 => array:5 [
//    "r030" => 348
//    "txt" => "Форинт"
//    "rate" => 0.112311
//    "cc" => "HUF"
//    "exchangedate" => "14.10.2024"
//  ]
//  7 => array:5 [
//    "r030" => 356
//    "txt" => "Індійська рупія"
//    "rate" => 0.48999
//    "cc" => "INR"
//    "exchangedate" => "14.10.2024"
//  ]
//  8 => array:5 [
//    "r030" => 360
//    "txt" => "Рупія"
//    "rate" => 0.0026435
//    "cc" => "IDR"
//    "exchangedate" => "14.10.2024"
//  ]
//  9 => array:5 [
//    "r030" => 376
//    "txt" => "Новий ізраїльський шекель"
//    "rate" => 10.956
//    "cc" => "ILS"
//    "exchangedate" => "14.10.2024"
//  ]
//  10 => array:5 [
//    "r030" => 392
//    "txt" => "Єна"
//    "rate" => 0.2764
//    "cc" => "JPY"
//    "exchangedate" => "14.10.2024"
//  ]
//  11 => array:5 [
//    "r030" => 398
//    "txt" => "Теньге"
//    "rate" => 0.085038
//    "cc" => "KZT"
//    "exchangedate" => "14.10.2024"
//  ]
//  12 => array:5 [
//    "r030" => 410
//    "txt" => "Вона"
//    "rate" => 0.030488
//    "cc" => "KRW"
//    "exchangedate" => "14.10.2024"
//  ]
//  13 => array:5 [
//    "r030" => 484
//    "txt" => "Мексиканське песо"
//    "rate" => 2.1203
//    "cc" => "MXN"
//    "exchangedate" => "14.10.2024"
//  ]
//  14 => array:5 [
//    "r030" => 498
//    "txt" => "Молдовський лей"
//    "rate" => 2.3276
//    "cc" => "MDL"
//    "exchangedate" => "14.10.2024"
//  ]
//  15 => array:5 [
//    "r030" => 554
//    "txt" => "Новозеландський долар"
//    "rate" => 25.0842
//    "cc" => "NZD"
//    "exchangedate" => "14.10.2024"
//  ]
//  16 => array:5 [
//    "r030" => 578
//    "txt" => "Норвезька крона"
//    "rate" => 3.8345
//    "cc" => "NOK"
//    "exchangedate" => "14.10.2024"
//  ]
//  17 => array:5 [
//    "r030" => 643
//    "txt" => "Російський рубль"
//    "rate" => 0.42927
//    "cc" => "RUB"
//    "exchangedate" => "14.10.2024"
//  ]
//  18 => array:5 [
//    "r030" => 702
//    "txt" => "Сінгапурський долар"
//    "rate" => 31.5359
//    "cc" => "SGD"
//    "exchangedate" => "14.10.2024"
//  ]
//  19 => array:5 [
//    "r030" => 710
//    "txt" => "Ренд"
//    "rate" => 2.3583
//    "cc" => "ZAR"
//    "exchangedate" => "14.10.2024"
//  ]
//  20 => array:5 [
//    "r030" => 752
//    "txt" => "Шведська крона"
//    "rate" => 3.9648
//    "cc" => "SEK"
//    "exchangedate" => "14.10.2024"
//  ]
//  21 => array:5 [
//    "r030" => 756
//    "txt" => "Швейцарський франк"
//    "rate" => 48.0339
//    "cc" => "CHF"
//    "exchangedate" => "14.10.2024"
//  ]
//  22 => array:5 [
//    "r030" => 818
//    "txt" => "Єгипетський фунт"
//    "rate" => 0.848
//    "cc" => "EGP"
//    "exchangedate" => "14.10.2024"
//  ]
//  23 => array:5 [
//    "r030" => 826
//    "txt" => "Фунт стерлінгів"
//    "rate" => 53.7909
//    "cc" => "GBP"
//    "exchangedate" => "14.10.2024"
//  ]
//  24 => array:5 [
//    "r030" => 840
//    "txt" => "Долар США"
//    "rate" => 41.1891
//    "cc" => "USD"
//    "exchangedate" => "14.10.2024"
//  ]
//  25 => array:5 [
//    "r030" => 933
//    "txt" => "Білоруський рубль"
//    "rate" => 14.9713
//    "cc" => "BYN"
//    "exchangedate" => "14.10.2024"
//  ]
//  26 => array:5 [
//    "r030" => 944
//    "txt" => "Азербайджанський манат"
//    "rate" => 24.2246
//    "cc" => "AZN"
//    "exchangedate" => "14.10.2024"
//  ]
//  27 => array:5 [
//    "r030" => 946
//    "txt" => "Румунський лей"
//    "rate" => 9.0506
//    "cc" => "RON"
//    "exchangedate" => "14.10.2024"
//  ]
//  28 => array:5 [
//    "r030" => 949
//    "txt" => "Турецька ліра"
//    "rate" => 1.2012
//    "cc" => "TRY"
//    "exchangedate" => "14.10.2024"
//  ]
//  29 => array:5 [
//    "r030" => 960
//    "txt" => "СПЗ (спеціальні права запозичення)"
//    "rate" => 55.1171
//    "cc" => "XDR"
//    "exchangedate" => "14.10.2024"
//  ]
//  30 => array:5 [
//    "r030" => 975
//    "txt" => "Болгарський лев"
//    "rate" => 23.021
//    "cc" => "BGN"
//    "exchangedate" => "14.10.2024"
//  ]
//  31 => array:5 [
//    "r030" => 978
//    "txt" => "Євро"
//    "rate" => 45.0238
//    "cc" => "EUR"
//    "exchangedate" => "14.10.2024"
//  ]
//  32 => array:5 [
//    "r030" => 985
//    "txt" => "Злотий"
//    "rate" => 10.4972
//    "cc" => "PLN"
//    "exchangedate" => "14.10.2024"
//  ]
//  33 => array:5 [
//    "r030" => 12
//    "txt" => "Алжирський динар"
//    "rate" => 0.3121
//    "cc" => "DZD"
//    "exchangedate" => "14.10.2024"
//  ]
//  34 => array:5 [
//    "r030" => 50
//    "txt" => "Така"
//    "rate" => 0.3435
//    "cc" => "BDT"
//    "exchangedate" => "14.10.2024"
//  ]
//  35 => array:5 [
//    "r030" => 51
//    "txt" => "Вірменський драм"
//    "rate" => 0.106432
//    "cc" => "AMD"
//    "exchangedate" => "14.10.2024"
//  ]
//  36 => array:5 [
//    "r030" => 214
//    "txt" => "Домініканське песо"
//    "rate" => 0.68698
//    "cc" => "DOP"
//    "exchangedate" => "14.10.2024"
//  ]
//  37 => array:5 [
//    "r030" => 364
//    "txt" => "Іранський ріал"
//    "rate" => 9.177E-5
//    "cc" => "IRR"
//    "exchangedate" => "14.10.2024"
//  ]
//  38 => array:5 [
//    "r030" => 368
//    "txt" => "Іракський динар"
//    "rate" => 0.031466
//    "cc" => "IQD"
//    "exchangedate" => "14.10.2024"
//  ]
//  39 => array:5 [
//    "r030" => 417
//    "txt" => "Сом"
//    "rate" => 0.48953
//    "cc" => "KGS"
//    "exchangedate" => "14.10.2024"
//  ]
//  40 => array:5 [
//    "r030" => 422
//    "txt" => "Ліванський фунт"
//    "rate" => 0.000461
//    "cc" => "LBP"
//    "exchangedate" => "14.10.2024"
//  ]
//  41 => array:5 [
//    "r030" => 434
//    "txt" => "Лівійський динар"
//    "rate" => 8.7057
//    "cc" => "LYD"
//    "exchangedate" => "14.10.2024"
//  ]
//  42 => array:5 [
//    "r030" => 458
//    "txt" => "Малайзійський ринггіт"
//    "rate" => 9.9961
//    "cc" => "MYR"
//    "exchangedate" => "14.10.2024"
//  ]
//  43 => array:5 [
//    "r030" => 504
//    "txt" => "Марокканський дирхам"
//    "rate" => 4.2532
//    "cc" => "MAD"
//    "exchangedate" => "14.10.2024"
//  ]
//  44 => array:5 [
//    "r030" => 586
//    "txt" => "Пакистанська рупія"
//    "rate" => 0.14836
//    "cc" => "PKR"
//    "exchangedate" => "14.10.2024"
//  ]
//  45 => array:5 [
//    "r030" => 682
//    "txt" => "Саудівський ріял"
//    "rate" => 10.9876
//    "cc" => "SAR"
//    "exchangedate" => "14.10.2024"
//  ]
//  46 => array:5 [
//    "r030" => 704
//    "txt" => "Донг"
//    "rate" => 0.0016778
//    "cc" => "VND"
//    "exchangedate" => "14.10.2024"
//  ]
//  47 => array:5 [
//    "r030" => 764
//    "txt" => "Бат"
//    "rate" => 1.27814
//    "cc" => "THB"
//    "exchangedate" => "14.10.2024"
//  ]
//  48 => array:5 [
//    "r030" => 784
//    "txt" => "Дирхам ОАЕ"
//    "rate" => 11.2224
//    "cc" => "AED"
//    "exchangedate" => "14.10.2024"
//  ]
//  49 => array:5 [
//    "r030" => 788
//    "txt" => "Туніський динар"
//    "rate" => 13.6305
//    "cc" => "TND"
//    "exchangedate" => "14.10.2024"
//  ]
//  50 => array:5 [
//    "r030" => 860
//    "txt" => "Узбецький сум"
//    "rate" => 0.0032417
//    "cc" => "UZS"
//    "exchangedate" => "14.10.2024"
//  ]
//  51 => array:5 [
//    "r030" => 901
//    "txt" => "Новий тайванський долар"
//    "rate" => 1.30179
//    "cc" => "TWD"
//    "exchangedate" => "14.10.2024"
//  ]
//  52 => array:5 [
//    "r030" => 934
//    "txt" => "Туркменський новий манат"
//    "rate" => 11.7771
//    "cc" => "TMT"
//    "exchangedate" => "14.10.2024"
//  ]
//  53 => array:5 [
//    "r030" => 941
//    "txt" => "Сербський динар"
//    "rate" => 0.39422
//    "cc" => "RSD"
//    "exchangedate" => "14.10.2024"
//  ]
//  54 => array:5 [
//    "r030" => 972
//    "txt" => "Сомоні"
//    "rate" => 3.8702
//    "cc" => "TJS"
//    "exchangedate" => "14.10.2024"
//  ]
//  55 => array:5 [
//    "r030" => 981
//    "txt" => "Ларі"
//    "rate" => 15.1127
//    "cc" => "GEL"
//    "exchangedate" => "14.10.2024"
//  ]
//  56 => array:5 [
//    "r030" => 986
//    "txt" => "Бразильський реал"
//    "rate" => 7.5853
//    "cc" => "BRL"
//    "exchangedate" => "14.10.2024"
//  ]
//  57 => array:5 [
//    "r030" => 959
//    "txt" => "Золото"
//    "rate" => 108906.86
//    "cc" => "XAU"
//    "exchangedate" => "14.10.2024"
//  ]
//  58 => array:5 [
//    "r030" => 961
//    "txt" => "Срібло"
//    "rate" => 1285.58
//    "cc" => "XAG"
//    "exchangedate" => "14.10.2024"
//  ]
//  59 => array:5 [
//    "r030" => 962
//    "txt" => "Платина"
//    "rate" => 40196.03
//    "cc" => "XPT"
//    "exchangedate" => "14.10.2024"
//  ]
//  60 => array:5 [
//    "r030" => 964
//    "txt" => "Паладій"
//    "rate" => 44469.4
//    "cc" => "XPD"
//    "exchangedate" => "14.10.2024"
//  ]
//]
