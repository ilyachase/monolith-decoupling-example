<?php

declare(strict_types=1);

namespace App\Common\Dto;

readonly class Delivery
{
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
