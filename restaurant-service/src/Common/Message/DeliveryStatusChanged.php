<?php

declare(strict_types=1);

namespace App\Common\Message;

use App\Common\Dto\Delivery as DeliveryDto;

readonly class DeliveryStatusChanged
{
    public function __construct(private DeliveryDto $delivery)
    {
    }

    public function getDelivery(): DeliveryDto
    {
        return $this->delivery;
    }
}
