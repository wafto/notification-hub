<?php

namespace Database\Factories;

use App\Channels\Resolver;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subject = Arr::random([
            'USER',
            'POST',
            'ITEM',
            'PRODUCT',
            'ADMIN',
            'ORDER',
        ]);

        $verb = Arr::random([
            'CREATED',
            'UPDATED',
            'DELETED',
            'STORED',
            'CANCELLED',
            'PAID',
            'REFUNDED',
            'BANNED',
        ]);

        $payload = [
            'user_id' => fake()->numerify('#######'),
        ];

        return [
            'event_id' => Str::orderedUuid(),
            'event_type' => sprintf('%s_%s', $subject, $verb),
            'payload' => $payload,
        ];
    }
}
