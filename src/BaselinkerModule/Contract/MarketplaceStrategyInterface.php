<?php

declare(strict_types=1);

namespace App\BaselinkerModule\Contract;

use App\BaselinkerModule\DTO\OrderDTO;

interface MarketplaceStrategyInterface
{
    
    public function supports(string $source): bool;

    public function map(array $orderData): OrderDTO;
}