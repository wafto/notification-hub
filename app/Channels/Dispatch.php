<?php

namespace App\Channels;

use App\Rules\ChannelAvailable;
use App\Rules\EventType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
}
