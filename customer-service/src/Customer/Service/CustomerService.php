<?php

declare(strict_types=1);

namespace App\Customer\Service;

use App\Common\Client\CourierServiceClient;
use App\Common\Client\RestaurantServiceClient;
use App\Common\Dto\Order as OrderDto;
use App\Common\Exception\EntityNotFoundException;
use App\Customer\Dto\CreateOrderRequest;
use App\Customer\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

readonly class CustomerService
{
    public function __construct(
        private RestaurantServiceClient $restaurantServiceClient,
        private CourierServiceClient $deliveryServiceClient,
        private EntityManagerInterface $customerEntityManager
    ) {
    }

    public function createOrder(CreateOrderRequest $createOrderRequest): int
    {
        if (!($restaurant = $this->restaurantServiceClient->getRestaurant($createOrderRequest->getRestaurantId()))) {
            throw new EntityNotFoundException();
        }

        $newOrder = (new Order())
            ->setRestaurantId($restaurant->getId())
            ->setStatus(Order::STATUS_NEW);

        $this->customerEntityManager->persist($newOrder);
        $this->customerEntityManager->flush();

        $orderDto = new OrderDto($newOrder->getId(), $newOrder->getStatus(), $newOrder->getRestaurantId(), $newOrder->getDeliveryId());

        if ($this->restaurantServiceClient->acceptOrder($orderDto)) {
            $newOrder->setStatus(Order::STATUS_ACCEPTED);
            $newDelivery = $this->deliveryServiceClient->createDelivery($orderDto);
            $newOrder->setDeliveryId($newDelivery->getId());
        } else {
            $newOrder->setStatus(Order::STATUS_DECLINED);
        }

        $this->customerEntityManager->persist($newOrder);
        $this->customerEntityManager->flush();

        return $newOrder->getId();
    }

    public function changeOrderStatus(int $orderId, string $orderStatus): void
    {
        if (!$orderId) {
            throw new EntityNotFoundException();
        }

        $order = $this->customerEntityManager->find(Order::class, $orderId);
        if (!$order) {
            throw new EntityNotFoundException();
        }

        // We do not validate anything for simplicity
        $order->setStatus($orderStatus);

        $this->customerEntityManager->persist($order);
        $this->customerEntityManager->flush();
    }
}
