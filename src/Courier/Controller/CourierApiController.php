<?php

declare(strict_types=1);

namespace App\Courier\Controller;

use App\Common\Client\CustomerServiceClient;
use App\Common\Dto\Delivery;
use App\Common\Dto\Order;
use App\Common\Exception\EntityNotFoundException;
use App\Courier\Dto\ChangeDeliveryStatusRequest;
use App\Courier\Service\CourierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/courier', defaults: ['_format' => 'json'])]
class CourierApiController extends AbstractController
{
    #[Route('/deliveries', methods: 'PATCH')]
    public function changeDeliveryStatus(
        #[MapRequestPayload] ChangeDeliveryStatusRequest $request,
        CourierService $courierService,
        CustomerServiceClient $customerServiceClient,
        NormalizerInterface $normalizer
    ): JsonResponse {
        try {
            $changedDelivery = $courierService->changeDeliveryStatus($request->getDeliveryId(), $request->getStatus());
        } catch (EntityNotFoundException) {
            return new JsonResponse(['message' => 'Delivery or related order not found'], Response::HTTP_BAD_REQUEST);
        }

        $newOrderStatus = match ($request->getStatus()) {
            Delivery::STATUS_COURIER_ASSIGNED => Order::STATUS_COURIER_ASSIGNED,
            Delivery::STATUS_DELIVERING => Order::STATUS_DELIVERING,
            Delivery::STATUS_FAILED => Order::STATUS_FAILED,
            Delivery::STATUS_SUCCESSFUL => Order::STATUS_SUCCESSFUL,
        };
        $customerServiceClient->changeOrderStatus($changedDelivery->getRelatedOrderId(), $newOrderStatus);

        return new JsonResponse($normalizer->normalize($changedDelivery));
    }
}
