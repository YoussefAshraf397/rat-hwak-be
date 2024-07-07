<?php

namespace App\Command;

use App\Handler\ReviewHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;

#[AsCommand(
    name: 'app:get-dumps',
)]
class GetDumpsCommand extends Command
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $dotenv = new Dotenv(true);
        $dotenv
            ->usePutenv()
            ->bootEnv(dirname(__DIR__, 2) . '/.env');

        $this->reviewHandler = new ReviewHandler($entityManager);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->reviewHandler->getReviewsDumpFile();

        return Command::SUCCESS;
    }
}
