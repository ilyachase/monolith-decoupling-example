<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Dto\Restaurant;
use RuntimeException;

class RestaurantServiceClient extends AbstractHttpClient
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

    protected function getServiceName(): string
    {
        return 'restaurant';
    }
}
