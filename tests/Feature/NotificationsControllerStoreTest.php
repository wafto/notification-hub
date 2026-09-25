<?php

use function Pest\Laravel\postJson;

test('endpoint should return 201 status code', function () {
    $response = postJson('/api/v1/notifications', [
        'event_id' => '01a0d72c-c89d-7529-a2cd-3ca86c466975',
        'event_type' => 'USER_CREATED',
        'channels' => ['email', 'sms'],
        'payload' => [
            'user_id' => '1234567',
            'message_email' => 'Hello world on email!',
            'email' => 'example@testing.com',
            'message_sms' => 'Hello world on sms',
            'phone' => '5542554444',
        ],
    ]);

    $response->assertStatus(201);
});
