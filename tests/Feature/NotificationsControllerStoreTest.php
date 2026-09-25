<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

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

    assertDatabaseCount('notifications', 2);

    assertDatabaseCount('notification_statuses', 2);

    assertDatabaseHas('notifications', [
        'event_id' => '01a0d72c-c89d-7529-a2cd-3ca86c466975',
        'event_type' => 'USER_CREATED',
        'channel' => 'email',
    ]);

    assertDatabaseHas('notifications', [
        'event_id' => '01a0d72c-c89d-7529-a2cd-3ca86c466975',
        'event_type' => 'USER_CREATED',
        'channel' => 'sms',
    ]);

    assertDatabaseHas('notification_statuses', [
        'status' => 'pending',
    ]);

    $response->assertStatus(201);
});
