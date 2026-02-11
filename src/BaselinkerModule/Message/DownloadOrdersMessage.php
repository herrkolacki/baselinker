<?php

declare(strict_types=1);

namespace App\BaselinkerModule\Message;


readonly class DownloadOrdersMessage
{
    public function __construct(
        public int $sinceTime, 
        public string $source  
    ) {}
}