<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', defaults: ['_format' => 'json'])]
class CustomerApiController
{
    #[Route('/create-order')]
    public function createOrder(): Response
    {
        return new JsonResponse('ok');
    }
}