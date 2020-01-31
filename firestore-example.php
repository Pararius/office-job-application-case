<?php
declare(strict_types=1);

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Transaction;

$firestore = new FirestoreClient(...);

$doc = $firestore
    ->collection('CollectionName')
    ->document('ID')
;

$firestore->runTransaction(
    static function (Transaction $transaction) use ($doc, $data) {
        $transaction->set($doc, $data);
    }
);
