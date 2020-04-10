<?php

declare(strict_types=1);

namespace App;

use App\GoogleCloud\FirestoreFactory;
use Google\Cloud\Firestore\DocumentSnapshot;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ShowListingsCommand extends Command
{
    protected static $defaultName = 'listings:show';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firestoreClient = FirestoreFactory::create($_ENV['GOOGLE_CLOUD_PROJECT']);

        $docs = $firestoreClient
            ->collection('listings')
            ->documents()
        ;

        $table = new Table($output);

        $table->setHeaders([
            'listing_id',
            'agent_id',
            'postal_code',
            'house_number',
        ]);

        /** @var DocumentSnapshot $doc */
        foreach ($docs as $doc) {
            $table->addRow([
               $doc->data()['listing_id'],
               $doc->data()['agent_id'],
               $doc->data()['postal_code'],
               $doc->data()['house_number'],
            ]);
        }

        $table->render();

        return 0;
    }
}
