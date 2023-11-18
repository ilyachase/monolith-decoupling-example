<?php

declare(strict_types=1);

namespace App\Customer\Controller;

use App\Common\Exception\EntityNotFoundException;
use App\Customer\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceApiController extends AbstractController
{
    #[Route('/service-customer/orders', methods: 'POST')]
    public function createDelivery(Request $request, CustomerService $customerService): JsonResponse
    {
        $orderId = (int) $request->get('orderId');
        $newOrderStatus = (string) $request->get('newOrderStatus');

        try {
            $customerService->changeOrderStatus($orderId, $newOrderStatus);
        } catch (EntityNotFoundException) {
            return new JsonResponse(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse();
    }
}
