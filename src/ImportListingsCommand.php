<?php

declare(strict_types=1);

namespace App;

use Google\Auth\Credentials\InsecureCredentials;
use Google\Cloud\PubSub\PubSubClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'listings:import')]
final class ImportListingsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new PubSubClient([
            'credentials' => new InsecureCredentials(),
            'projectId' => $_ENV['GOOGLE_CLOUD_PROJECT'],
        ]);

        $topic = $client->topic('imported-listings');
        if (!$topic->exists()) {
            $topic->create();
        }
        $subscription = $client->subscription('process-imported-listings', 'imported-listings');

        if (!$subscription->exists()) {
            $subscription->create();
        }

        $fp = fopen('./data.csv', 'rb');

        $headers = fgetcsv($fp);
        while ($row = fgetcsv($fp)) {
            $listing = array_combine($headers, $row);

            $output->writeln(sprintf('=> Importing: %s', $listing['listing_id']));

            $topic->publish([
                'data' => json_encode($listing, JSON_THROW_ON_ERROR, 512),
            ]);
        }

        return 0;
    }
}
