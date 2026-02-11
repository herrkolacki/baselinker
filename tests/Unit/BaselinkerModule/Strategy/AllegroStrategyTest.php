<?php

declare(strict_types=1);

namespace App\Tests\Unit\BaselinkerModule\Strategy;

use App\BaselinkerModule\DTO\OrderDTO;
use App\BaselinkerModule\Strategy\AllegroStrategy;
use PHPUnit\Framework\TestCase;

class AllegroStrategyTest extends TestCase
{
    private AllegroStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AllegroStrategy();
    }

    public function testItSupportsOnlyAllegroSource(): void
    {
       
        $this->assertTrue($this->strategy->supports('allegro'));
        $this->assertFalse($this->strategy->supports('amazon'));
        $this->assertFalse($this->strategy->supports('ebay'));
    }

    public function testItMapsBaselinkerDataToOrderDTO(): void
    {
        
        $inputData = [
            'order_id' => '123456',
            'order_source' => 'allegro',
            'email' => 'klient@allegro.pl',
            'payment_amount' => 150.99,
            'order_status_id' => '500',
            'currency' => 'PLN' 
        ];

        
        $dto = $this->strategy->map($inputData);

        $this->assertInstanceOf(OrderDTO::class, $dto);
        $this->assertSame('123456', $dto->externalOrderId);
        $this->assertSame('allegro', $dto->source);
        $this->assertSame('klient@allegro.pl', $dto->email);
        $this->assertEquals(150.99, $dto->totalPrice);
        $this->assertSame('PLN', $dto->currency);
    }

    public function testItHandlesMissingEmailGracefully(): void
    {
         $inputData = [
            'order_id' => '789',
            'payment_amount' => 50.00,
            'order_status_id' => '100'
        ];

        $dto = $this->strategy->map($inputData);
        $this->assertStringContainsString('brak-email', $dto->email);
    }
}