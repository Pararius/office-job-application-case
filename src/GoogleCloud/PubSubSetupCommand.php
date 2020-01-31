<?php

declare(strict_types=1);

namespace App\GoogleCloud;

use Assert\Assertion;
use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\Topic;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PubSubSetupCommand extends Command
{
    protected static $defaultName = 'gcloud:setup:pubsub';

    /** @var Topic[] */
    private array $topics = [];

    /** @var array */
    private array $subscriptions = [];

    public function __construct(iterable $topics, iterable $subscriptions)
    {
        foreach ($topics as $topic) {
            Assertion::isInstanceOf($topic, Topic::class);
            $this->topics[] = $topic;
        }

        foreach ($subscriptions as $subscription) {
            $options = [];
            if (\is_array($subscription)) {
                $options = $subscription['options'];
                $subscription = $subscription['subscription'];
            }

            Assertion::isInstanceOf($subscription, Subscription::class);
            $this->subscriptions[] = [$subscription, $options];
        }

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Set up PubSub topics and subscriptions');

        foreach ($this->topics as $topic) {
            if (!$topic->exists()) {
                $topic->create();
            }

            $output->writeln("=> topic <comment>{$topic->name()}</comment>");
        }

        /** @var Subscription $subscription */
        /** @var array $options */
        foreach ($this->subscriptions as [$subscription, $options]) {
            if (!$subscription->exists($options)) {
                $subscription->create($options);
            }

            $output->writeln("=> subscription <comment>{$subscription->name()}</comment>");
        }

        return 0;
    }
}
