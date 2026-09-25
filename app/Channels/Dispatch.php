<?php

namespace App\Channels;

use App\Rules\EventType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        ] = Validator::make($data, [
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
            ],
            'channels.*' => [
                Rule::in($this->resolver->channelsNames()),
                'distinct',
            ],
            'payload' => [
                'required',
                'array',
            ],
        ])->validate();

        dump($eventId, $eventType, $channels, $payload);
    }
}
