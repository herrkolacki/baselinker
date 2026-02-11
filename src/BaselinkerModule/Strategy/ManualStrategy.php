<?php

declare(strict_types=1);

namespace App\BaselinkerModule\Strategy;

use App\BaselinkerModule\Contract\MarketplaceStrategyInterface;
use App\BaselinkerModule\DTO\OrderDTO;

class ManualStrategy implements MarketplaceStrategyInterface
{
    public function supports(string $source): bool
    {
        return in_array($source, ['manual', 'personal_pickup', 'shop']);
    }

    public function map(array $orderData): OrderDTO
    {
        return new OrderDTO(
            externalOrderId: (string) $orderData['order_id'],
            source: 'manual', 
            email: $orderData['email'] ?? 'brak-email@manual.pl',
            totalPrice: (float) ($orderData['payment_amount'] ?? 0.0),
            currency: $orderData['currency'] ?? 'PLN',
            status: (string) ($orderData['order_status_id'] ?? '0')
        );
    }
}