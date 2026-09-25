<?php

namespace App\Channels;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class Resolver
{
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
        $names = [];

        foreach ($channels as $channel) {
            if ($names[$channel->name()] ?? false) {
                throw new InvalidArgumentException(
                    sprintf('Duplicated service name found for %s!', $channel->name())
                );
            }
            $names[$channel->name()] = true;
        }

        return $channels;
    }

    /**
     * Returns the names of the available and loaded channels services.
     * @return Collection<string>
     */
    public function channelsNames(): Collection
    {
        return collect($this->channels)->map(fn ($ch) => $ch->name());
    }
}
