<?php

namespace App\Channels;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class Resolver
{
    /**
     * @param array<string, ?Channel> $channels
     */
    private array $channels;

    /**
     * Constructor, check the ChannelsServiceProvider for adding new services.
     */
    public function __construct(Channel ...$channels)
    {
        $this->channels = $this->verifyChannels($channels);
    }

    /**
     * Verifies for no duplicated service channels via name.
     */
    private function verifyChannels(array $channels): array
    {
        $mapping = [];

        foreach ($channels as $channel) {
            if ($mapping[$channel->name()] ?? false) {
                throw new InvalidArgumentException(
                    sprintf('Duplicated service name found for %s!', $channel->name())
                );
            }
            $mapping[$channel->name()] = $channel;
        }

        return $mapping;
    }

    /**
     * Returns the names of the available and loaded channels services.
     * @return Collection<string>
     */
    public function channelsNames(): Collection
    {
        return collect(array_keys($this->channels));
    }

    /**
     * Return channel resolution.
     */
    public function forChannel(string $name): ?Channel
    {
        return $this->channels[$name] ?? null;
    }
}
