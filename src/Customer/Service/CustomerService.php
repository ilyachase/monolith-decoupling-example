<?php

declare(strict_types=1);

namespace App\Customer\Service;

use App\Courier\Service\CourierService;
use App\Customer\Dto\CreateOrderRequest;
use App\Customer\Entity\Order;
use App\Common\Exception\EntityNotFoundException;
use App\Restaurant\Service\RestaurantService;
use Doctrine\ORM\EntityManagerInterface;

readonly class CustomerService
{
    public function __construct(
        private RestaurantService $restaurantService,
        private CourierService $deliveryService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function createOrder(CreateOrderRequest $createOrderRequest): int
    {
        if (!($restaurant = $this->restaurantService->getRestaurant($createOrderRequest->getRestaurantId()))) {
            throw new EntityNotFoundException();
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

    public function changeOrderStatus(?int $orderId, string $orderStatus): void
    {
        if (!$orderId) {
            throw new EntityNotFoundException();
        }

        $order = $this->entityManager->find(Order::class, $orderId);
        if (!$order) {
            throw new EntityNotFoundException();
        }

        $order->setStatus($orderStatus);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
