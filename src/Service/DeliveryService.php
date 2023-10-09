<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Delivery;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

readonly class DeliveryService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    public function createDelivery(Order $newOrder): Delivery
    {
        $newDelivery = (new Delivery())
            ->setStatus(Delivery::STATUS_NEW)
            ->setRelatedOrder($newOrder);

        $this->entityManager->persist($newDelivery);
        $this->entityManager->flush();

        return $newDelivery;
    }
}
