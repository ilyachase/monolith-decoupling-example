<?php

declare(strict_types=1);

namespace App\Common\Message;

use App\Common\Dto\Delivery;

readonly class DeliveryCreated
{
    public function __construct(private Delivery $delivery)
    {
    }

    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }
}
