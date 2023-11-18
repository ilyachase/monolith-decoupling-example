<?php

declare(strict_types=1);

namespace App\Customer\Entity;

use App\Customer\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    public const STATUS_NEW = 'new';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_COURIER_ASSIGNED = 'courier_assigned';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SUCCESSFUL = 'successful';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(name: 'restaurant_id')]
    private ?int $restaurantId = null;

    #[ORM\Column(name: 'delivery_id')]
    private ?int $deliveryId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function setRestaurantId(int $restaurantId): static
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }

    public function setDeliveryId(int $deliveryId): static
    {
        $this->deliveryId = $deliveryId;

        return $this;
    }

    public function getRestaurantId(): ?int
    {
        return $this->restaurantId;
    }

    public function getDeliveryId(): ?int
    {
        return $this->deliveryId;
    }
}
