<?php
declare(strict_types=1);


use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\Topic;

// PUBSUB READ
$subscription = new Subscription(...);

$messages = $subscription->pull();

foreach ($messages as $message) {
    $subscription->acknowledge($message);
}



// PUBSUB WRITE
$topic = new Topic(...);
$topic->publish([]);
