<?php

namespace App\Channels;

final class ChannelsResolver
{
    private array $channels;

    public function __construct(Channel ...$channels)
    {
        $this->channels = $channels;
    }
}
