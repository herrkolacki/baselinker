<?php

declare(strict_types=1);

namespace App\BaselinkerModule\DTO;

readonly class OrderDTO
{
    public function __construct(
        public string $externalOrderId, // ID z Allegro/Amazon
        public string $source,          // np. 'allegro'
        public string $email,
        public float $totalPrice,
        public string $currency,
        public string $status
    ) {}
}