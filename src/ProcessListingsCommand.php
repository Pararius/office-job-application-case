<?php

declare(strict_types=1);

namespace App;

use Google\Auth\Credentials\InsecureCredentials;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\PubSub\PubSubClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'listings:process')]
final class ProcessListingsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pubSubClient = new PubSubClient([
            'credentials' => new InsecureCredentials(),
            'projectId' => $_ENV['GOOGLE_CLOUD_PROJECT'],
            'requestTimeout' => 10,
        ]);

        $topic = $pubSubClient->topic('imported-listings');
        if (!$topic->exists()) {
            $topic->create();
        }

        $subscription = $pubSubClient->subscription('process-imported-listings', 'imported-listings');

        if (!$subscription->exists()) {
            $subscription->create();
        }

        $firestoreClient = new FirestoreClient([
            'projectId' => $_ENV['GOOGLE_CLOUD_PROJECT'],
        ]);

        $count = 0;
        while ($count < 10) {
            foreach ($subscription->pull() as $message) {
                $listing = json_decode($message->data(), true, 512, JSON_THROW_ON_ERROR);

                $output->writeln(sprintf('=> Processing: %s', $listing['listing_id']));

                $firestoreClient
                    ->collection('listings')
                    ->document($listing['listing_id'])
                    ->set($listing)
                ;

                $subscription->acknowledge($message);

                ++$count;
            }
        }

        return 0;
    }
}
