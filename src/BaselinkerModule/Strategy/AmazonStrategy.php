<?php
declare(strict_types=1);

namespace App\BaselinkerModule\Strategy;

use App\BaselinkerModule\Contract\MarketplaceStrategyInterface;
use App\BaselinkerModule\DTO\OrderDTO;

class AmazonStrategy implements MarketplaceStrategyInterface
{
    public function supports(string $source): bool
    {
       return $source === 'amazon';
    }

    public function map(array $orderData): OrderDTO
    {
        return new OrderDTO(
            externalOrderId: (string) $orderData['order_id'],
            source: 'amazon',
            email: $orderData['email'] ?? 'ukryty-email@amazon.com', 
            totalPrice: (float) ($orderData['payment_amount'] ?? 0.0),
            currency: $orderData['currency'] ?? 'EUR', 
            status: (string) ($orderData['order_status_id'] ?? '0')
        );
    }
}