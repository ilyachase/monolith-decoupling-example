<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateOrderRequest;
use App\Exception\OrderCannotBeCreatedException;
use App\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', defaults: ['_format' => 'json'])]
class CustomerApiController extends AbstractController
{
    #[Route('/create-order')]
    public function createOrder(
        #[MapRequestPayload] CreateOrderRequest $createOrderRequest,
        CustomerService $customerService,
    ): JsonResponse {
        try {
            $newOrderId = $customerService->createOrder($createOrderRequest);
        } catch (OrderCannotBeCreatedException) {
            return new JsonResponse(['message' => 'Order cannot be created'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['id' => $newOrderId]);
    }
}
