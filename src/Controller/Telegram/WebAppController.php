<?php
declare(strict_types=1);

namespace App\Controller\Telegram;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WebAppController extends AbstractController
{

    #[Route('/webapp', name: 'webapp')]
    public function index(): Response
    {
        return $this->render('telegramm/webapp.html.twig');
    }
}