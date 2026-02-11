<?php

declare(strict_types=1);

namespace App\BaselinkerModule\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class BaselinkerClient
{
    private const API_URL = 'https://api.baselinker.com/connector.php';

    public function __construct(
        private string $apiKey,
        private HttpClientInterface $httpClient
    ) {}

    public function request(string $method, array $parameters = []): array
    {
        $response = $this->httpClient->request(Request::METHOD_POST, self::API_URL, [
            'body' => [
                'method' => $method,
                'parameters' => json_encode($parameters),
            ],
            'headers' => [
                'X-BLToken' => $this->apiKey,
            ],
        ]);

        $data = $response->toArray();

       
        if (($data['status'] ?? '') === 'ERROR') {
            throw new \RuntimeException(sprintf(
                'Baselinker API Error: %s (Code: %s)', 
                $data['error_message'] ?? 'Unknown', 
                $data['error_code'] ?? 'N/A'
            ));
        }

        return $data;
    }
}