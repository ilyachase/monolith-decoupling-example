<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;

readonly class RestaurantService
{
    public function __construct(private RestaurantRepository $restaurantRepository)
    {
    }

    public function getRestaurant(int $restaurantId): ?Restaurant
    {
        // for the sake of the example, instead of real business logic, we will just
        // make sure that the referenced restaurant exists
        return $this->restaurantRepository->find($restaurantId);
    }

    public function acceptOrder(Order $newOrder): bool
    {
        // for the sake of the example, let's assume for now that the order can always be served
        return true;
    }
}
