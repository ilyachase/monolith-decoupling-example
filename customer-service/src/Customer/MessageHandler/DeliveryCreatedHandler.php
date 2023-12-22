<?php

declare(strict_types=1);

namespace App\Customer\MessageHandler;

use App\Common\Message\DeliveryCreated;
use App\Customer\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeliveryCreatedHandler
{
    public function __construct(private OrderRepository $orderRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(DeliveryCreated $message): void
    {
        $orderEntity = $this->orderRepository->find($message->getDelivery()->getRelatedOrderId());
        if (!$orderEntity) {
            return;
        }

        $orderEntity->setDeliveryId($message->getDelivery()->getId());
        $this->entityManager->flush();
    }
}
