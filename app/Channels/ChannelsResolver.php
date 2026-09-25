<?php

namespace App\Channels;

use InvalidArgumentException;

final class ChannelsResolver
{
    private array $channels;

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
}
