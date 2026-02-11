<?php

declare(strict_types=1);

namespace App\Tests\Unit\BaselinkerModule\MessageHandler;

use App\BaselinkerModule\Api\BaselinkerClient;
use App\BaselinkerModule\Contract\MarketplaceStrategyInterface;
use App\BaselinkerModule\DTO\OrderDTO;
use App\BaselinkerModule\Message\DownloadOrdersMessage;
use App\BaselinkerModule\MessageHandler\DownloadOrdersHandler;
use App\BaselinkerModule\Service\StrategyRegistry;
use PHPUnit\Framework\TestCase;

class DownloadOrdersHandlerTest extends TestCase
{
    public function testItFetchesAndProcessesOrders(): void
    {
        // 1. Mockujemy Klienta API (żeby nie dzwonił do Baselinkera naprawdę)
        $clientMock = $this->createMock(BaselinkerClient::class);
        $clientMock->expects($this->once()) // Oczekujemy, że metoda zostanie wywołana raz
            ->method('request')
            ->willReturn([
                'orders' => [
                    ['order_id' => 100, 'order_source' => 'amazon'] // Symulujemy 1 zamówienie
                ]
            ]);

        // 2. Mockujemy Strategię
        $strategyMock = $this->createMock(MarketplaceStrategyInterface::class);
        $strategyMock->method('map')->willReturn(
            new OrderDTO('100', 'amazon', 'test@test.pl', 10.0, 'EUR', '200')
        );

        // 3. Mockujemy Rejestr, żeby zwrócił naszą mockowaną strategię
        $registryMock = $this->createMock(StrategyRegistry::class);
        $registryMock->method('getStrategyForSource')
            ->with('amazon') // Oczekujemy, że zapyta o 'amazon'
            ->willReturn($strategyMock);

        // 4. Tworzymy Handler z naszymi mockami
        $handler = new DownloadOrdersHandler($clientMock, $registryMock);

        // 5. Uruchamiamy (Invoke)
        // Ponieważ Twój handler używa "echo", możemy przechwycić ten tekst, żeby sprawdzić czy działa
        $this->expectOutputRegex('/Baselinker API zwróciło: 1 zamówień/');

        $handler(new DownloadOrdersMessage(123456, 'all'));
    }
}