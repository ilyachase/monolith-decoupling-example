<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateOrderRequest;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;

readonly class RestaurantService
{
    public function __construct(private RestaurantRepository $restaurantRepository)
    {
    }

    public function acceptOrder(CreateOrderRequest $createOrderRequest): ?Restaurant
    {
        // for the sake of the example, instead of real business logic, we will just
        // make sure that the referenced restaurant exists
        return $this->restaurantRepository->find($createOrderRequest->getRestaurantId());
    }
}
