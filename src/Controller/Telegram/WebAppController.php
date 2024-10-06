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
        private readonly string $currencyJWT = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyMzU0NzQsImV4cCI6MTcyODIzOTA3NCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.LOsKrkxNJC1AlpyQaxhqWeaoSxXuczXjzZkbm19jrIrRNWx9lRgEZgs2slUtpBC8V_szkxwCu-C3nKoyX6UwDg5Gbdg8y7k0480hUzYWxHo7HN-06DqJT-dd-R9kSzSPRZlR5tiy4kNmtyfMHxE4NYNfIKRf8x30OTzlugwd0Czu4g_lXmOtHKpE6sH-8gl4w19tuAy1LXv0Q48_5C1DOjhbKnJkAhF6B0tbx5Xb3YjMuF7kOLOxTDEdeiJkGI8X1emSiR3hJUPPaueQq9PV2YD3GzgLo5p225yoL-FDo463c1NyZ4AoBsLXP6cdzOMI3SoOKF5_91yCF0lZIbqh2A',
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