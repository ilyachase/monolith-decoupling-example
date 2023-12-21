<?php

declare(strict_types=1);

namespace App\Customer\Service;

use App\Common\Client\RestaurantServiceClient;
use App\Common\Dto\Order as OrderDto;
use App\Common\Exception\EntityNotFoundException;
use App\Common\Message\OrderCreated;
use App\Customer\Dto\CreateOrderRequest;
use App\Customer\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CustomerService
{
    public function __construct(
        private RestaurantServiceClient $restaurantServiceClient,
        private EntityManagerInterface $customerEntityManager,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function createOrder(CreateOrderRequest $createOrderRequest): Order
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

        // todo: change to async
        //        if ($this->restaurantServiceClient->acceptOrder($orderDto)) {
        //            $newOrder->setStatus(Order::STATUS_ACCEPTED);
        //            $newDelivery = $this->deliveryServiceClient->createDelivery($orderDto);
        //            $newOrder->setDeliveryId($newDelivery->getId());
        //        } else {
        //            $newOrder->setStatus(Order::STATUS_DECLINED);
        //        }

        $this->customerEntityManager->persist($newOrder);
        $this->customerEntityManager->flush();

        $this->messageBus->dispatch(new OrderCreated($orderDto));

        return $newOrder;
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
