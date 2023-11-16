<?php

declare(strict_types=1);

namespace App\Common\Dto;

readonly class Restaurant
{
    public function __construct(private int $id, private string $name)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
