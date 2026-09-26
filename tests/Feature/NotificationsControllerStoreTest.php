<?php

use App\Events\NotificationCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Sleep;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

$body = [
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
];

Sleep::fake();

test('endpoints on success return only the same event id and type provided in the request', function () use ($body) {
    $response = postJson('/api/v1/notifications', $body);

    $response
        ->assertStatus(201)
        ->assertExactJson([
            'event_id' => $body['event_id'],
            'event_type' => $body['event_type'],
        ]);
});

test('endpoint should return 201 and create two pending notifications', function () use ($body) {
    $response = postJson('/api/v1/notifications', $body);

    assertDatabaseCount('notifications', 2);

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


test('notification created event should be dispatched', function () use ($body)  {
    Event::fake();

    $response = postJson('/api/v1/notifications', $body);

    Event::assertDispatched(NotificationCreated::class, 2);

    $response->assertStatus(201);
});

test('prevent duplicated entries when event uuid is duplicated on the request side', function () use ($body) {
    $response = postJson('/api/v1/notifications', $body);
    assertDatabaseCount('notifications', 2);
    $response->assertStatus(201);

    $response = postJson('/api/v1/notifications', $body);
    assertDatabaseCount('notifications', 2);
    $response->assertStatus(422);
});

test('validates uuid that only accepts version 7', function () use ($body) {
    $response = postJson('/api/v1/notifications', [
        ...$body,
        'event_id' => 'c53c494c-0e4e-4e90-a69b-2d3831147cba',
    ]);

    assertDatabaseCount('notifications', 0);

    $response->assertStatus(422);

    $response = postJson('/api/v1/notifications', [
        ...$body,
        'event_id' => '1234',
    ]);

    assertDatabaseCount('notifications', 0);

    $response->assertStatus(422);
});

test('validates event type when it does not match UPPER_SNAKE_CASE', function () use ($body) {
    $response = postJson('/api/v1/notifications', [
        ...$body,
        'event_type' => 'user_CREATED',
    ]);

    assertDatabaseCount('notifications', 0);

    $response->assertStatus(422);
});

test('validates channels to be existing ones', function () use ($body) {
    $response = postJson('/api/v1/notifications', [
        ...$body,
        'channels' => ['email', 'sms', 'fakechain'],
    ]);

    assertDatabaseCount('notifications', 0);

    $response->assertStatus(422);
});

test('validates duplicated channels', function () use ($body) {
    $response = postJson('/api/v1/notifications', [
        ...$body,
        'channels' => ['email', 'sms', 'sms', 'email'],
    ]);

    assertDatabaseCount('notifications', 0);

    $response->assertStatus(422);
});

