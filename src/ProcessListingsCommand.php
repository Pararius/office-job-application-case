<?php

declare(strict_types=1);

namespace App;

use App\GoogleCloud\FirestoreFactory;
use App\GoogleCloud\PubSubFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProcessListingsCommand extends Command
{
    protected static $defaultName = 'listings:process';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pubSubClient = PubSubFactory::client($_ENV['GOOGLE_CLOUD_PROJECT']);

        $topic = $pubSubClient->topic('imported-listings');
        if (!$topic->exists()) {
            $topic->create();
        }

        $subscription = $pubSubClient->subscription('process-imported-listings', 'imported-listings');

        if (!$subscription->exists()) {
            $subscription->create();
        }

        $firestoreClient = FirestoreFactory::create($_ENV['GOOGLE_CLOUD_PROJECT']);

        while ($messages = $subscription->pull()) {
            foreach ($messages as $message) {
                $listing = json_decode($message->data(), true, 512, JSON_THROW_ON_ERROR);

                $output->writeln(sprintf('=> Importing: %s', $listing['listing_id']));

                $firestoreClient
                    ->collection('listings')
                    ->document($listing['listing_id'])
                    ->create($listing)
                ;

                $subscription->acknowledge($message);
            }
        }

        return 0;
    }
}
