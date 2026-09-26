<?php

namespace App\Channels\Services;

use App\Channels\Channel;
use App\Models\Notification;
use App\Rules\ChannelUserId;
use App\Rules\PhoneNumber;
use Illuminate\Support\Arr;
use Illuminate\Support\Sleep;

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

    public function send(Notification $notification): bool
    {
        /** Lets fake the sms send notification by making 50% success or fail and adding some sleeping time */
        $status = Arr::random([true, false]);
        $time = Arr::random([500, 1000, 1500, 2000]);

        Sleep::for($time)->milliseconds();
        logger()->info('Sent SMS notification', $notification->payload);

        return $status;
    }
}
