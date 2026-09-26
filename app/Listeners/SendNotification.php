<?php

namespace App\Listeners;

use App\Channels\Resolver;
use App\Enums\Status;
use App\Events\NotificationCreated;
use DateTimeInterface;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use RuntimeException;

class SendNotification implements ShouldBeUnique, ShouldQueue
{
    use InteractsWithQueue;

    public $failOnTimeout = true;

    public function __construct(
        private Resolver $resolver,
    ) {}

    public function uniqueId(NotificationCreated $event): string
    {
        return sprintf(
            'send-notification:%s:%s:%s',
            $event->notification->channel,
            $event->notification->event_id,
            $event->notification->event_type,
        );
    }

    public function retryUntil(): DateTimeInterface
    {
        return now()->plus(minutes: 1);
    }

    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;
        $channel = $notification->channel;
        $service = $this->resolver->forChannel($channel);

        if ($service == null) {
            $this->fail(new RuntimeException(sprintf('Unable to find service for channel %s', $channel)));
            return;
        }

        $notification->addStatus(Status::STARTED);

        $sent = rescue(
            callback: fn () => $service->send($notification),
            rescue: fn () => false,
            report: fn () => true,
        );

        $notification->addStatus($sent ? Status::DELIVERED : Status::FAILED);
    }
}


