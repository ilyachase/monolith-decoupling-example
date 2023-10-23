<?php

declare(strict_types=1);

namespace App\Courier\Entity;

use App\Customer\Entity\Order;
use App\Courier\Repository\DeliveryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
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
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $RelatedOrder = null;

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

    public function getRelatedOrder(): ?Order
    {
        return $this->RelatedOrder;
    }

    public function setRelatedOrder(Order $RelatedOrder): static
    {
        $this->RelatedOrder = $RelatedOrder;

        return $this;
    }
}
