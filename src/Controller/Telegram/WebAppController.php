<?php
declare(strict_types=1);

namespace App\Controller\Telegram;

use App\DTO\Request\CurrencyConvertRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WebAppController extends AbstractController
{
    public function __construct(
        private readonly string $currencyJWT = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyNDAwNjAsImV4cCI6MTc1OTc3NjA2MCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoibWFpbDFAZXhhbXBsZS5jb20ifQ.YVUQIwQn3q5nm3LfI1RPtNM0vS0B_DWtNg-iLz_qDq4_MTPzp9aXh1AOmx8zPkP911M4PZQxX-t-MHpCbFvyeV__4p9ed34lHjEvmIuPJ-PcLsV4Nbco_fjZ0XPT0uu4U5CuCXsUUvfKi6x_xfEjICY5oZMyY0s6rmPu52WFrDF2c7qp_VfxRyB82VaP6Ri2zWmNjdwalYOp1yo78JPOY-CQ-oQaK2ImnzDYOBb24HTYyvqFnanN6cJWUsXBLhdqcIIW6oc26rmCvnv_T48J7iHt0eVyRjAflY3-yg_BW7WuAJE5G7C4rGVotswEJdercLSKHC0toOzGoi9a6gOhAQ',
    ){}

    #[Route('/webapp', name: 'webapp')]
    public function index(): Response
    {
        return $this->render('telegramm/webapp.html.twig', [
            'currencyChoices' => CurrencyConvertRequest::CURRENCY_CHOICES,
            'api_jwt' => $this->currencyJWT,
        ]);
    }
}