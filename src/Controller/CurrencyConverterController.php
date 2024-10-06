<?php


declare(strict_types=1);

namespace App\Controller;

use App\Attribute\Validator;
use App\DTO\Request\CurrencyConvertRequest;
use App\DTO\Response\CurrencyConvertResponse;

use App\Service\Currency\CurrencyConversionServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class CurrencyConverterController extends AbstractController
{
    public function __construct(
        private readonly CurrencyConversionServiceInterface $currencyConversionService,
    ){ }

    #[Route('/api/currency/convert', name: 'app_currency_converter_convert', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the converted amount',
        content: new OA\JsonContent(ref: new Model(type: CurrencyConvertResponse::class))
    )]
    #[OA\Tag(name: 'Currency Converter')]
    #[Security(name: 'Bearer')]
    public function convert(
        #[MapQueryString] #[Validator] CurrencyConvertRequest $currency
    ): JsonResponse
    {
        try {
            $convertedAmount = $this->currencyConversionService->convert($currency);
            return $this->json($convertedAmount);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
