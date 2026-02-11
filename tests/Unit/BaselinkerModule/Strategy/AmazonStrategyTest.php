<?php

declare(strict_types=1);

namespace App\Tests\Unit\BaselinkerModule\Strategy;

use App\BaselinkerModule\DTO\OrderDTO;
use App\BaselinkerModule\Strategy\AmazonStrategy;
use PHPUnit\Framework\TestCase;

class AmazonStrategyTest extends TestCase
{
    private AmazonStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AmazonStrategy();
    }

    public function testItSupportsOnlyAmazon(): void
    {
        $this->assertTrue($this->strategy->supports('amazon'));
        $this->assertFalse($this->strategy->supports('allegro'));
        $this->assertFalse($this->strategy->supports('manual'));
    }

    public function testItMapsAmazonDataToDTO(): void
    {
       
        $inputData = [
            'order_id' => '999',
            'order_source' => 'amazon',
            'email' => 'client@amazon.de', 
            'payment_amount' => 50.00,
            'currency' => 'EUR',
            'order_status_id' => '200'
        ];

        
        $dto = $this->strategy->map($inputData);
        $this->assertInstanceOf(OrderDTO::class, $dto);
        $this->assertSame('amazon', $dto->source);
        $this->assertSame('EUR', $dto->currency);
        $this->assertStringContainsString('@', $dto->email); 
    }
}