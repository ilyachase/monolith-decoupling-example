<?php

declare(strict_types=1);

namespace App\Courier\Entity;

use App\Courier\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
#[ORM\Table(name: 'delivery')]
class Delivery
{
    public const STATUS_NEW = 'new';
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

    #[ORM\Column(name: 'related_order_id')]
    private ?int $relatedOrderId = null;

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

    public function getRelatedOrderId(): ?int
    {
        return $this->relatedOrderId;
    }

    public function setRelatedOrderId(int $relatedOrderId): static
    {
        $this->relatedOrderId = $relatedOrderId;

        return $this;
    }
}