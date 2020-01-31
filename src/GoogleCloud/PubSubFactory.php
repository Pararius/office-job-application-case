<?php

declare(strict_types=1);

namespace App\GoogleCloud;

use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\Topic;

final class PubSubFactory
{
    public static function client(string $projectId): PubSubClient
    {
        return new PubSubClient([
            'projectId' => $projectId,
        ]);
    }

    public static function topic(PubSubClient $client, string $name): Topic
    {
        return $client->topic($name);
    }

    public static function subscription(Topic $topic, string $name): Subscription
    {
        return $topic->subscription($name);
    }
}
