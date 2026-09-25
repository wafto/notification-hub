<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
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
            'channels' => [],
            'payload' => $payload,
        ];
    }

    public function sms(): static
    {
        return $this->state(fn (array $attributes) => [
            'channels' => [
                ...$attributes['channels'],
                'sms',
            ],
            'payload' => [
                ...$attributes['payload'],
                'message_sms' => fake()->sentence(5),
                'phone' => fake()->numerify('##########')
            ],
        ]);
    }

    public function email(): static
    {
        return $this->state(fn (array $attributes) => [
            'channels' => [
                ...$attributes['channels'],
                'email',
            ],
            'payload' => [
                ...$attributes['payload'],
                'message_email' => fake()->sentence(30),
                'email' => fake()->safeEmail(),
            ],
        ]);
    }
}
