<?php

namespace App\Http\Controllers\API\V1;

use App\Channels\Dispatch;
use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    #[BodyParameter(
        name: 'event_id',
        description: 'Client provided uuid v7 as a first safeguard for idempotency.',
        type: 'string',
        format: 'uuid',
        example: '01a0d72c-c89d-7529-a2cd-3ca86c466975',
    )]
    #[BodyParameter(
        name: 'event_type',
        description: 'Client provided event type required in UPPER_SNAKE_CASE format.',
        type: 'string',
        format: 'UPPER_SNAKE_CASE',
        example: 'USER_CREATED',
    )]
    #[BodyParameter(
        name: 'channels',
        description: 'A List of channels to send the notification, available: sms, email',
        type: 'array',
        format: 'array<string>',
        example: ['sms', 'email'],
    )]
    #[BodyParameter(
        name: 'payload',
        description: 'A list of properties needed for the channels.',
        type: 'array',
        format: 'array<string, mixed>',
        example: [
            'user_id' => '1234567',
            'message_email' => 'Hello world on email!',
            'email' => 'example@testing.com',
            'message_sms' => 'Hello world on sms',
            'phone' => '5542554444',
        ],
    )]
    public function store(Request $request, Dispatch $dispatch): JsonResponse
    {
        $dispatch($request->all());

        return response()->json([
            'event_id' => $request->input('event_id'),
            'event_type' => $request->input('event_type'),
        ], 201);
    }
}
