<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Dto\Order;
use App\Common\Dto\Restaurant;
use RuntimeException;

class RestaurantServiceClient extends AbstractSymfonyControllerResolvingClient
{
    public function getRestaurant(int $restaurantId): ?Restaurant
    {
        $response = $this->sendServiceRequest('/service-restaurant/restaurants/'.$restaurantId);

        if (404 === $response->getStatusCode()) {
            return null;
        }

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException('Unexpected response code');
        }

        return $this->serializer->deserialize($response->getContent(), Restaurant::class, 'json');
    }

    public function acceptOrder(Order $orderDto): bool
    {
        $response = $this->sendServiceRequest(
            uri: '/service-restaurant/orders/actions/accept',
            requestBody: $this->serializer->normalize($orderDto),
            method: 'POST'
        );

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException('Unexpected response code');
        }

        return $this->serializer->decode(data: $response->getContent(), format: 'json');
    }
}
