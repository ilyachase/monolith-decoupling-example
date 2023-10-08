<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateOrderRequest;
use App\Entity\Order;
use App\Exception\OrderCannotBeCreatedException;
use Doctrine\ORM\EntityManagerInterface;

readonly class CustomerService
{
    public function __construct(
        private RestaurantService $restaurantService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function createOrder(CreateOrderRequest $createOrderRequest): int
    {
        if (!($restaurant = $this->restaurantService->acceptOrder($createOrderRequest))) {
            throw new OrderCannotBeCreatedException();
        }

        $newOrder = (new Order())
            ->setRestaurant($restaurant)
            ->setStatus(Order::STATUS_ACCEPTED);

        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();

        return $newOrder->getId();
    }
}
