<?php

namespace App\Command;

use App\Service\ScrapingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twilio\Exceptions\ConfigurationException;

#[AsCommand(
    name: 'app:search:rdv',
    description: 'Add a short description for your command',
)]
class SearchRdvCommand extends Command
{
    public function __construct(
        private readonly ScrapingService $scrapingService,
    )
    {
        parent::__construct();
    }

    /**
     * @throws ConfigurationException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->scrapingService->searchRdv();

        return Command::SUCCESS;
    }
}
