<?php

declare(strict_types=1);

namespace App\Courier\MessageHandler;

use App\Common\Dto\Delivery as DeliveryDto;
use App\Common\Message\DeliveryCreated;
use App\Common\Message\OrderAccepted;
use App\Courier\Service\CourierService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class OrderAcceptedHandler
{
    public function __construct(private CourierService $courierService, private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(OrderAccepted $message)
    {
        $delivery = $this->courierService->createDelivery($message->getOrder());
        $deliveryDto = new DeliveryDto($delivery->getId(), $delivery->getStatus(), $delivery->getRelatedOrderId());

        $this->messageBus->dispatch(new DeliveryCreated($deliveryDto));
    }
}
