<?php

declare(strict_types=1);

namespace App;

use Google\Auth\Credentials\InsecureCredentials;
use Google\Cloud\Firestore\DocumentSnapshot;
use Google\Cloud\Firestore\FirestoreClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'listings:show')]
final class ShowListingsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firestoreClient = new FirestoreClient([
            'credentials' => new InsecureCredentials(),
            'projectId' => $_ENV['GOOGLE_CLOUD_PROJECT'],
        ]);

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
