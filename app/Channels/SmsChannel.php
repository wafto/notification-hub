<?php

namespace App\Channels;

use App\Rules\ChannelUserId;
use App\Rules\PhoneNumber;

final class SmsChannel implements Channel
{
    public function name(): string
    {
        return 'sms';
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                new ChannelUserId,
            ],
            'message_sms' => [
                'required',
                'string',
                'max:80',
            ],
            'phone' => [
                'required',
                new PhoneNumber,
            ],
        ];
    }
}
