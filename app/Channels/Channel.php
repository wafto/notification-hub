<?php

namespace App\Channels;

use App\Models\Notification;

interface Channel
{
    /**
     * The given name that should match when resolving channels.
     * @return string
     */
    public function name(): string;

    /**
     * Rules for payload validation.
     * @return array<string, array<mixed>>
     */
    public function rules(): array;

    /**
     * Send the notification on the given channel, return true if it was delivered or false otherwise.
     * @return bool
     */
    public function send(Notification $notification): bool;
}
