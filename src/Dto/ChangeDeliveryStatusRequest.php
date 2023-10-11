<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Delivery;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ChangeDeliveryStatusRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        private int $deliveryId,
        #[Assert\Type('string')]
        #[Assert\Choice(choices: [
            Delivery::STATUS_COURIER_ASSIGNED,
            Delivery::STATUS_DELIVERING,
            Delivery::STATUS_FAILED,
            Delivery::STATUS_SUCCESSFUL,
        ])]
        private string $status
    ) {
    }

    public function getDeliveryId(): int
    {
        return $this->deliveryId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
