<?php

declare(strict_types=1);

namespace App\Courier\Controller;

use App\Courier\Dto\ChangeDeliveryStatusRequest;
use App\Courier\Entity\Delivery;
use App\Customer\Entity\Order;
use App\Common\Exception\EntityNotFoundException;
use App\Courier\Service\CourierService;
use App\Customer\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/courier', defaults: ['_format' => 'json'])]
class CourierApiController extends AbstractController
{
    #[Route('/delivery', methods: 'PATCH')]
    public function changeDeliveryStatus(
        #[MapRequestPayload] ChangeDeliveryStatusRequest $request,
        CourierService $courierService,
        CustomerService $customerService,
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
        $customerService->changeOrderStatus($changedDelivery->getRelatedOrderId(), $newOrderStatus);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('api')
            ->toArray();

        return new JsonResponse($normalizer->normalize($changedDelivery, context: $context));
    }
}
