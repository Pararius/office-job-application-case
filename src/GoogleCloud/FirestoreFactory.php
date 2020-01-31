<?php

declare(strict_types=1);

namespace App\GoogleCloud;

use Google\Cloud\Firestore\FirestoreClient;

final class FirestoreFactory
{
    public static function create(string $projectId): FirestoreClient
    {
        return new FirestoreClient([
            'projectId' => $projectId,
        ]);
    }
}
