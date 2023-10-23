<?php

declare(strict_types=1);

namespace App\Courier\Service;

use App\Courier\Entity\Delivery;
use App\Customer\Entity\Order;
use App\Common\Exception\EntityNotFoundException;
use App\Courier\Repository\DeliveryRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class CourierService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeliveryRepository $deliveryRepository
    ) {
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

    public function changeDeliveryStatus(int $deliveryId, string $status): Delivery
    {
        $delivery = $this->deliveryRepository->find($deliveryId);
        if (!$delivery || !$delivery->getRelatedOrder()?->getId()) {
            throw new EntityNotFoundException();
        }

        // For simplicity, let's assume we can change from any status to any other status
        $delivery->setStatus($status);

        $this->entityManager->persist($delivery);
        $this->entityManager->flush();

        return $delivery;
    }
}
