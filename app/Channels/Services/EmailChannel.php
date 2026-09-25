<?php

namespace App\Channels\Services;

use App\Channels\Channel;
use App\Rules\ChannelUserId;

final class EmailChannel implements Channel
{
    public function name(): string
    {
        return 'email';
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                new ChannelUserId,
            ],
            'message_email' => [
                'required',
                'string',
                'max:1200',
            ],
            'email' => [
                'required',
                'email',
            ],
        ];
    }
}
