<?php

declare(strict_types=1);

namespace App\Tests;

use App\ImportListingsCommand;
use App\ProcessListingsCommand;
use App\ShowListingsCommand;
use Google\Cloud\Firestore\DocumentReference;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\PubSub\PubSubClient;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use function trim;

final class ImportAndProcessListingsTest extends TestCase
{
    #[Test]
    public function it_imports(): void
    {
        $tester = new CommandTester(
            new ImportListingsCommand()
        );

        static::assertSame(
            0,
            $tester->execute([])
        );

        static::assertSame(
            <<<DISPLAY
            => Importing: 54b2b0c7-242a-5293-8208-20be987a90f3
            => Importing: 2591007e-b700-509a-bde3-89e68b471df8
            => Importing: 6f801e21-bbac-5ee0-869f-055e8ebfcf52
            => Importing: f2737494-80e8-52a4-b361-6782036348d2
            => Importing: e8c3f68d-2fd7-5744-b4d9-1ed6fb40c159
            => Importing: 65aad7e6-424b-5b04-bb83-b034f784dcd9
            => Importing: 2aea560e-3160-5484-aedd-82cc51e74541
            => Importing: 51376889-0788-5edc-bee3-04b4569a7a20
            => Importing: d4e47f93-a025-5731-abc6-da3630af193b
            => Importing: 311256ee-e484-54e5-8a59-5d40a7693e81
            DISPLAY,
            trim($tester->getDisplay())
        );
    }

    #[Test]
    public function it_processes(): void
    {
        $tester = new CommandTester(
            new ProcessListingsCommand()
        );

        static::assertSame(
            0,
            $tester->execute([])
        );

        static::assertSame(
            <<<DISPLAY
            => Processing: 54b2b0c7-242a-5293-8208-20be987a90f3
            => Processing: 2591007e-b700-509a-bde3-89e68b471df8
            => Processing: 6f801e21-bbac-5ee0-869f-055e8ebfcf52
            => Processing: f2737494-80e8-52a4-b361-6782036348d2
            => Processing: e8c3f68d-2fd7-5744-b4d9-1ed6fb40c159
            => Processing: 65aad7e6-424b-5b04-bb83-b034f784dcd9
            => Processing: 2aea560e-3160-5484-aedd-82cc51e74541
            => Processing: 51376889-0788-5edc-bee3-04b4569a7a20
            => Processing: d4e47f93-a025-5731-abc6-da3630af193b
            => Processing: 311256ee-e484-54e5-8a59-5d40a7693e81
            DISPLAY,
            trim($tester->getDisplay())
        );
    }

    #[Test]
    public function it_shows_after_processing(): void
    {
        $tester = new CommandTester(
            new ShowListingsCommand()
        );

        static::assertSame(
            0,
            $tester->execute([])
        );

        static::assertSame(
            <<<DISPLAY
            +--------------------------------------+--------------------------------------+-------------+--------------+
            | listing_id                           | agent_id                             | postal_code | house_number |
            +--------------------------------------+--------------------------------------+-------------+--------------+
            | 2591007e-b700-509a-bde3-89e68b471df8 | 21b0352b-0cb1-52b2-bbaf-3c7f93f19153 | 2131XE      | 8            |
            | 2aea560e-3160-5484-aedd-82cc51e74541 | 21b0352b-0cb1-52b2-bbaf-3c7f93f19153 | 2341SB      | 34           |
            | 311256ee-e484-54e5-8a59-5d40a7693e81 | 0ce0b99c-09f9-517b-8e57-9e6b01ea55a1 | 7311JR      | 66           |
            | 51376889-0788-5edc-bee3-04b4569a7a20 | 6b46d871-f748-55e3-8ff4-f11bfd6ff135 | 5963AG      | 24b          |
            | 54b2b0c7-242a-5293-8208-20be987a90f3 | 6b51d92b-f310-5d82-9404-068929c780fd | 7524P       | 10           |
            | 65aad7e6-424b-5b04-bb83-b034f784dcd9 | 6b51d92b-f310-5d82-9404-068929c780fd | 5642JC      | 63           |
            | 6f801e21-bbac-5ee0-869f-055e8ebfcf52 | 6b46d871-f748-55e3-8ff4-f11bfd6ff135 |             |              |
            | d4e47f93-a025-5731-abc6-da3630af193b | 6b51d92b-f310-5d82-9404-068929c780fd | 6211SJ      | 62           |
            | e8c3f68d-2fd7-5744-b4d9-1ed6fb40c159 | 6b51d92b-f310-5d82-9404-068929c780fd | 6221AV      | 23           |
            | f2737494-80e8-52a4-b361-6782036348d2 | 6b46d871-f748-55e3-8ff4-f11bfd6ff135 | 5988NH      | 0ong         |
            +--------------------------------------+--------------------------------------+-------------+--------------+
            DISPLAY,
            trim($tester->getDisplay())
        );
    }

    public static function setUpBeforeClass(): void
    {
        $pubSubClient = new PubSubClient([
            'projectId' => $_ENV['GOOGLE_CLOUD_PROJECT'],
        ]);

        $topic = $pubSubClient->topic('imported-listings');
        if ($topic->exists()) {
            $topic->delete();
        }

        $subscription = $pubSubClient->subscription('process-imported-listings', 'imported-listings');
        if ($subscription->exists()) {
            $subscription->delete();
        }

        /** @var DocumentReference[] $docs */
        $docs = (new FirestoreClient(['projectId' => $_ENV['GOOGLE_CLOUD_PROJECT']]))
            ->collection('listings')
            ->listDocuments()
        ;

        foreach ($docs as $doc) {
            $doc->delete();
        }
    }
}
