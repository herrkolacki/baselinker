<?php

declare(strict_types=1);

namespace App\BaselinkerModule\MessageHandler;

use App\BaselinkerModule\Api\BaselinkerClient;
use App\BaselinkerModule\Message\DownloadOrdersMessage;
use App\BaselinkerModule\Service\StrategyRegistry;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DownloadOrdersHandler
{
    public function __construct(
        private BaselinkerClient $baselinkerClient,
        private StrategyRegistry $strategyRegistry
    ) {}

    public function __invoke(DownloadOrdersMessage $message): void
    {
        echo "\n[SYSTEM] Rozpoczynam pobieranie danych zgodnie z API...\n";

        try {
           
            $response = $this->baselinkerClient->request('getOrders', [
                'date_from' => 1, 
                'get_unconfirmed_orders' => true,
            ]);

            $orders = $response['orders'] ?? [];
            $count = count($orders);

            echo "[SYSTEM] Baselinker API zwróciło: $count zamówień.\n";

            foreach ($orders as $orderData) {
                $this->processSingleOrder($orderData);
            }

        } catch (\Throwable $e) {
            echo "[BŁĄD KRYTYCZNY]: " . $e->getMessage() . "\n";
        }
    }

    private function processSingleOrder(array $orderData): void
    {
        
        $source = strtolower($orderData['order_source'] ?? 'unknown');
        $id = $orderData['order_id'];

        echo "------------------------------------------------\n";
        echo " Przetwarzam ID: $id | Źródło z API: '$source'\n";

        try {
        
            $strategy = $this->strategyRegistry->getStrategyForSource($source);
            $dto = $strategy->map($orderData);

            echo " [SUKCES] Znaleziono strategię dla: " . strtoupper($dto->source) . "\n";
            echo "   -> Email: " . $dto->email . "\n";
            echo "   -> Kwota: " . $dto->totalPrice . " " . $dto->currency . "\n";

        } catch (\RuntimeException $e) {
         
            echo " [INFO] Pominięto - brak obsługi w systemie dla źródła '$source'.\n";
        } catch (\Throwable $e) {
            echo " [BŁĄD] Nie udało się zmapować: " . $e->getMessage() . "\n";
        }
        echo "------------------------------------------------\n";
    }
}