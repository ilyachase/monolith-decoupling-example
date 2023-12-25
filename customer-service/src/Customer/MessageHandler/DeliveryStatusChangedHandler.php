<?php

declare(strict_types=1);

namespace App\Customer\MessageHandler;

use App\Common\Dto\Delivery as DeliveryDto;
use App\Common\Dto\Order as OrderDto;
use App\Common\Message\DeliveryStatusChanged;
use App\Customer\Service\CustomerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeliveryStatusChangedHandler
{
    public function __construct(private CustomerService $customerService)
    {
    }

    public function __invoke(DeliveryStatusChanged $message): void
    {
        $newOrderStatus = match ($message->getDelivery()->getStatus()) {
            DeliveryDto::STATUS_COURIER_ASSIGNED => OrderDto::STATUS_COURIER_ASSIGNED,
            DeliveryDto::STATUS_DELIVERING => OrderDto::STATUS_DELIVERING,
            DeliveryDto::STATUS_FAILED => OrderDto::STATUS_FAILED,
            DeliveryDto::STATUS_SUCCESSFUL => OrderDto::STATUS_SUCCESSFUL,
        };
        $this->customerService->changeOrderStatus($message->getDelivery()->getRelatedOrderId(), $newOrderStatus);
    }
}
