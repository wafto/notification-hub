<?php

namespace App\Channels\Services;

use App\Channels\Channel;
use App\Models\Notification;
use App\Rules\ChannelUserId;
use Illuminate\Support\Facades\Mail;

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

    public function send(Notification $notification): bool
    {
        /** Only for testing purpose sending raw message. */
        Mail::raw($notification->payload->get('message_email'), fn ($message) => $message
            ->to($notification->payload->get('email'))
            ->subject('Hello from NotificationHub!')
        );

        return true;
    }
}
