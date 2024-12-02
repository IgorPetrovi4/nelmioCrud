<?php
declare(strict_types=1);

namespace App\Controller;

use App\Attribute\QueryParam;
use App\DTO\SalaryCalculateDTO;
use App\Exception\UserNotFoundException;
use App\Repository\SalaryRepository;
use App\Repository\UserRepository;
use App\Service\Exchange\Interface\ExchangeInterface;
use App\Service\SalaryCalculate\Interface\SalaryCalculatorInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

#[Route('api/salary')]
class SalaryCalculateController extends AbstractController
{
    public function __construct(
        private readonly SalaryCalculatorInterface $averageSalaryCalculator,
        private readonly SalaryCalculatorInterface $salaryIncreaseCalculator,
        private readonly ExchangeInterface $exchange,
        private readonly SalaryRepository $salaryRepository,
        private readonly UserRepository   $userRepository
    ) { }

    #[Route('/user/{id}/calculate', name: 'app_user_salary_calculate_show', methods: ['GET'])]
    #[OA\Parameter(
        name: "id",
        description: "The ID of the user",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer", example: 2)
    )]
    #[OA\Parameter(
        name: "percentage",
        description: SalaryCalculateDTO::PERCENTAGE_DESCRIPTION,
        in: "query",
        required: true,
        schema: new OA\Schema(type: "string", example: SalaryCalculateDTO::PERCENTAGE_EXAMPLE)
    )]
    #[OA\Parameter(
        name: "currency",
        description: SalaryCalculateDTO::CURRENCY_DESCRIPTION,
        in: "query",
        required: true,
        schema: new OA\Schema(
            type: "string",
            enum: SalaryCalculateDTO::CURRENCY_CHOICES,
            example: SalaryCalculateDTO::CURRENCY_EXAMPLE
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns a salary calculation for the user',
        content: new OA\JsonContent(ref: new Model(type: SalaryCalculateDTO::class, groups: ['salary_calculate']))
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found',
    )]
    #[OA\Tag(name: 'Salary')]
    #[Security(name: 'Bearer')]
    public function salaryCalculateShow(int $id, #[QueryParam] SalaryCalculateDTO $salaryCalculate): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }
        $salaryCalculate->setUser($user);
        $totalSalary = $this->salaryRepository->findTotalSalaryByUser($user->getId());

        $salaryCalculate->getUser()->setTotalSalary($totalSalary);
        $currency = $salaryCalculate->getCurrency();
        $percentage = $salaryCalculate->getPercentage();

        $exchangeRate = $this->exchange->exchangeRates($user, $currency);
        $salaryCalculate->setExchangeRate($exchangeRate);

        $averageSalary = $this->averageSalaryCalculator->calculate($user, null);
        $salaryCalculate->setAverageSalary($averageSalary);

        $salaryIncrease = $this->salaryIncreaseCalculator->calculate($user, $percentage);
        $salaryCalculate->setSalaryIncrease($salaryIncrease);

        return $this->json($salaryCalculate, 200, [], ['groups' => 'salary_calculate']);
    }
}
