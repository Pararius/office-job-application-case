<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProcessListingsCommand extends Command
{
    protected static $defaultName = 'listings:process';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Receive the data from PubSub and publish to Firestore');
        return 0;
    }
}
