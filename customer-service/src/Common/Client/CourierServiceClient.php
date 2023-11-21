<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Dto\Delivery;
use App\Common\Dto\Order;
use RuntimeException;

class CourierServiceClient extends AbstractHttpClient
{
    public function createDelivery(Order $newOrder): Delivery
    {
        $response = $this->sendServiceRequest(
            uri: '/service-courier/deliveries',
            requestBody: $this->serializer->normalize($newOrder),
            method: 'POST'
        );

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException('Unexpected response code');
        }

        return $this->serializer->deserialize($response->getContent(), Delivery::class, 'json');
    }

    protected function getServiceName(): string
    {
        return 'courier';
    }
}
