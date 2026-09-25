<?php

namespace App\Channels;

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Rules\ChannelAvailable;
use App\Rules\EventType;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

final class Dispatch
{
    public function __construct(
        private Resolver $resolver,
        private Dispatcher $eventbus,
    ) {}

    public function __invoke(array $data)
    {
        [
            'event_id' => $eventId,
            'event_type' => $eventType,
            'channels' => $channels,
            'payload' => $payload,
        ] = $this->generalValidation($data);

        $data = $this->channelsValidation($channels, $payload);
        $notifications = $this->createNotifications($eventId, $eventType, $data);

        foreach ($notifications as $notification) {
            $this->eventbus->dispatch(new NotificationCreated($notification));
        }
    }

    protected function generalValidation(array $data): array
    {
        return Validator::make($data, [
            'event_id' => [
                'unique:notifications,event_id',
                'required',
                'string',
                'uuid:7',
            ],
            'event_type' => [
                'required',
                'string',
                'max:40',
                new EventType,
            ],
            'channels' => [
                'required',
                'array',
                new ChannelAvailable($this->resolver),
            ],
            'payload' => [
                'required',
                'array',
            ],
        ])->validate();
    }

    protected function channelsValidation(array $channels, array $payload): array
    {
        $validated = [];
        $errors = [];

        foreach ($channels as $channel) {
            $resolution = $this->resolver->forChannel($channel);

            if ($resolution == null) {
                throw new RuntimeException(sprintf('Unable to resolve channel %s!', $channel));
            }

            $rules = $resolution->rules();
            $keys = array_keys($rules);

            $validator = Validator::make(
                data: $payload,
                rules: $rules,
                attributes: array_combine($keys, $keys),
            );

            if ($validator->fails()) {
                $errors[$channel] = $validator->errors()->messages();
            } else {
                $validated[$channel] = $validator->validated();
            }
        }

        if (count($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return $validated;
    }

    protected function createNotifications(string $eventId, string $eventType, array $data): array
    {
        $notifications = [];

        foreach ($data as $channelName => $channelPayload) {
            $notifications[] = Notification::create([
                'event_id' => $eventId,
                'event_type' => $eventType,
                'channel' => $channelName,
                'payload' => $channelPayload,
            ]);
        }

        return $notifications;
    }
}
