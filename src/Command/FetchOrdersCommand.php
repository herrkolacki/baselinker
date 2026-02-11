<?php

declare(strict_types=1);

namespace App\Command;

use App\BaselinkerModule\Message\DownloadOrdersMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:fetch-orders',
    description: 'Pobiera zamówienia z Baselinkera i przetwarza je przez strategie.'
)]
class FetchOrdersCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Rozpoczynanie procesu pobierania zamówień...</info>');

        $sinceTime = 1;
        
        $message = new DownloadOrdersMessage($sinceTime, 'all_marketplaces');
        $this->messageBus->dispatch($message);

        $output->writeln('<comment>Wiadomość została wysłana do kolejki.</comment>');
        
        return Command::SUCCESS;
    }
}