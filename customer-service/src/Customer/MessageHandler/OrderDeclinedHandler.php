<?php

declare(strict_types=1);

namespace App\Customer\MessageHandler;

use App\Common\Message\OrderDeclined;
use App\Customer\Entity\Order;
use App\Customer\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class OrderDeclinedHandler
{
    public function __construct(private OrderRepository $orderRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(OrderDeclined $message): void
    {
        $orderEntity = $this->orderRepository->find($message->getOrder()->getId());
        if (!$orderEntity) {
            return;
        }

        $orderEntity->setStatus(Order::STATUS_DECLINED);
        $this->entityManager->flush();
    }
}
