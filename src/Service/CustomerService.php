<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateOrderRequest;
use App\Entity\Order;
use App\Exception\RestaurantNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

readonly class CustomerService
{
    public function __construct(
        private RestaurantService $restaurantService,
        private DeliveryService $deliveryService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function createOrder(CreateOrderRequest $createOrderRequest): int
    {
        if (!($restaurant = $this->restaurantService->getRestaurant($createOrderRequest->getRestaurantId()))) {
            throw new RestaurantNotFoundException();
        }

        $newOrder = (new Order())
            ->setRestaurant($restaurant)
            ->setStatus(Order::STATUS_NEW);

        if ($this->restaurantService->acceptOrder($newOrder)) {
            $newOrder->setStatus(Order::STATUS_ACCEPTED);
            $newDelivery = $this->deliveryService->createDelivery($newOrder);
            $newOrder->setDelivery($newDelivery);
        } else {
            $newOrder->setStatus(Order::STATUS_DECLINED);
        }

        $this->entityManager->persist($newOrder);
        $this->entityManager->flush();

        return $newOrder->getId();
    }
}
