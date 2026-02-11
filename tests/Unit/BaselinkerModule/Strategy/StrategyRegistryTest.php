<?php

declare(strict_types=1);

namespace App\Tests\Unit\BaselinkerModule\Service;

use App\BaselinkerModule\Contract\MarketplaceStrategyInterface;
use App\BaselinkerModule\Service\StrategyRegistry;
use PHPUnit\Framework\TestCase;

class StrategyRegistryTest extends TestCase
{
    public function testItReturnsCorrectStrategyForSource(): void
    {
        // 1. Tworzymy "udawane" strategie (Mocki)
        // Udajemy strategię Allegro
        $allegroStrategy = $this->createMock(MarketplaceStrategyInterface::class);
        $allegroStrategy->method('supports')->willReturnCallback(fn($s) => $s === 'allegro');

        // Udajemy strategię Amazon
        $amazonStrategy = $this->createMock(MarketplaceStrategyInterface::class);
        $amazonStrategy->method('supports')->willReturnCallback(fn($s) => $s === 'amazon');

        // 2. Wrzucamy je do rejestru
        $registry = new StrategyRegistry([$allegroStrategy, $amazonStrategy]);

        // 3. Testujemy
        $result = $registry->getStrategyForSource('amazon');

        // Sprawdzamy, czy zwrócony obiekt to ten sam, który udaje Amazona
        $this->assertSame($amazonStrategy, $result);
    }

    public function testItThrowsExceptionWhenNoStrategyFound(): void
    {
        $registry = new StrategyRegistry([]); // Pusty rejestr

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Brak strategii');

        $registry->getStrategyForSource('ebay');
    }
}