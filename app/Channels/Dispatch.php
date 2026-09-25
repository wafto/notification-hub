<?php

namespace App\Channels;

use App\Rules\ChannelAvailable;
use App\Rules\EventType;
use Illuminate\Support\Facades\Validator;

final class Dispatch
{
    public function __construct(
        private Resolver $resolver,
    ) {}

    public function __invoke(array $data)
    {
        $data = Validator::make($data, [
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

        dump($data);
    }
}
