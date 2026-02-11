<?php

declare(strict_types=1);

namespace App\BaselinkerModule\Service;

use App\BaselinkerModule\Contract\MarketplaceStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class StrategyRegistry
{
    /**
     * @param iterable<MarketplaceStrategyInterface> $strategies
     */
    public function __construct(
        #[AutowireIterator('app.marketplace_strategy')]
        private iterable $strategies
    ) {}

    public function getStrategyForSource(string $source): MarketplaceStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($source)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Brak strategii dla źródła: %s', $source));
    }
}