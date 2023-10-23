<?php
declare(strict_types=1);

namespace App\Customer\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateOrderRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        private int $restaurantId
    )
    {
    }

    public function getRestaurantId(): int
    {
        return $this->restaurantId;
    }
}