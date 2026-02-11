<?php

declare(strict_types=1);

namespace App\BaselinkerModule\Strategy;

use App\BaselinkerModule\Contract\MarketplaceStrategyInterface;
use App\BaselinkerModule\DTO\OrderDTO;

class AllegroStrategy implements MarketplaceStrategyInterface
{
    public function supports(string $source): bool
    {
        return $source === 'allegro';
    }

    public function map(array $orderData): OrderDTO
    {
      return new OrderDTO(
            externalOrderId: (string) $orderData['order_id'], 
            source: 'allegro',
            email: $orderData['email'] ?? 'brak-email@allegro.pl',
            totalPrice: (float) ($orderData['payment_amount'] ?? 0.0),
            currency: 'PLN', 
            status: $orderData['order_status_id'] 
        );
    }
}