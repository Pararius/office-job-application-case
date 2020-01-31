<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportListingsCommand extends Command
{
    protected static $defaultName = 'listings:import';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Import the data here and publish to PubSub');
        return 0;
    }
}
