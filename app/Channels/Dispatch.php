<?php

namespace App\Channels;

use App\Rules\ChannelAvailable;
use App\Rules\EventType;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

final class Dispatch
{
    public function __construct(
        private Resolver $resolver,
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

        dump($data, $eventId, $eventType);
    }

    public function generalValidation(array $data): array
    {
        return Validator::make($data, [
            'event_id' => [
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

    public function channelsValidation(array $channels, array $payload): array
    {
        $validated = [];

        foreach ($channels as $channel) {
            $resolution = $this->resolver->forChannel($channel);

            if ($resolution == null) {
                throw new RuntimeException(sprintf('Unable to resolve channel %s!', $channel));
            }

            $validated[$channel] = Validator::make($payload, $resolution->rules())
                ->validateWithBag($channel);
        }

        return $validated;
    }
}
