<?php

declare(strict_types=1);

namespace App\Common\Dto;

readonly class Delivery
{
    public const STATUS_NEW = 'new';
    public const STATUS_COURIER_ASSIGNED = 'courier_assigned';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SUCCESSFUL = 'successful';

    public function __construct(
        private ?int $id = null,
        private ?string $status = null,
        private ?int $relatedOrderId = null
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getRelatedOrderId(): ?int
    {
        return $this->relatedOrderId;
    }
}
