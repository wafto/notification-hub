<?php

namespace App\Channels;

use App\Rules\ChannelUserId;

class EmailChannel implements Channel
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
