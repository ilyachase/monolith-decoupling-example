<?php

declare(strict_types=1);

namespace App\Courier\Controller;

use App\Common\Dto\Delivery as DeliveryDto;
use App\Common\Exception\EntityNotFoundException;
use App\Common\Message\DeliveryStatusChanged;
use App\Courier\Dto\ChangeDeliveryStatusRequest;
use App\Courier\Service\CourierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/courier', defaults: ['_format' => 'json'])]
class CourierApiController extends AbstractController
{
    #[Route('/deliveries', methods: 'PATCH')]
    public function changeDeliveryStatus(
        #[MapRequestPayload] ChangeDeliveryStatusRequest $request,
        CourierService $courierService,
        NormalizerInterface $normalizer,
        MessageBusInterface $messageBus
    ): JsonResponse {
        try {
            $changedDelivery = $courierService->changeDeliveryStatus($request->getDeliveryId(), $request->getStatus());
        } catch (EntityNotFoundException) {
            return new JsonResponse(['message' => 'Delivery or related order not found'], Response::HTTP_BAD_REQUEST);
        }

        $messageBus->dispatch(new DeliveryStatusChanged(new DeliveryDto(
            $changedDelivery->getId(),
            $changedDelivery->getStatus(),
            $changedDelivery->getRelatedOrderId()
        )));

        return new JsonResponse($normalizer->normalize($changedDelivery));
    }
}
