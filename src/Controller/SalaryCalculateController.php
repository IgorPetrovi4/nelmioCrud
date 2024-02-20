<?php
declare(strict_types=1);


namespace App\Controller;

use App\DTO\SalaryCalculateDTO;
use App\Exception\UserNotFoundException;
use App\Repository\SalaryRepository;
use App\Repository\UserRepository;
use App\Service\Exchange\Interface\ExchangeInterface;
use App\Service\SalaryCalculate\Interface\SalaryCalculatorInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    ){ }


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
        description: "The percentage to increase the salary",
        in: "query",
        schema: new OA\Schema(type: "float", example: "10.5")
    )]
    #[OA\Parameter(
        name: "currency",
        description: "The exchange rate for the currency",
        in: "query",
        schema: new OA\Schema(
            type: "string",
            enum: ["USD", "EUR", "GBP", "CAD", "AUD"],
            example: "USD"
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
    public function salaryCalculateShow(int $id, SalaryCalculateDTO $salaryCalculate, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }
        $percentage = $request->query->get('percentage');
        $currency = $request->query->get('currency');
        $salaryCalculate->setUser($user);
        $salaryCalculate->getUser()->setTotalSalary($this->salaryRepository->findTotalSalaryByUser($user->getId()));
        $salaryCalculate->setCurrency($currency);
        $salaryCalculate->setExchangeRate($this->exchange->exchangeRates($user, $currency));
        $salaryCalculate->setAverageSalary($this->averageSalaryCalculator->calculate($user, null));
        $salaryCalculate->setSalaryIncrease($this->salaryIncreaseCalculator->calculate($user, $percentage));
        return $this->json($salaryCalculate, 200, [], ['groups' => 'salary_calculate']);
    }

}