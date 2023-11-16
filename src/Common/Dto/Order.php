<?php

declare(strict_types=1);

namespace App\Common\Dto;

readonly class Order
{
    public function __construct(
        private ?int $id = null,
        private ?string $status = null,
        private ?int $restaurantId = null,
        private ?int $deliveryId = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getRestaurantId(): ?int
    {
        return $this->restaurantId;
    }

    public function getDeliveryId(): ?int
    {
        return $this->deliveryId;
    }
}
