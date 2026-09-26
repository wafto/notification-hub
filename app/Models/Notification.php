<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsFluent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'event_id',
    'event_type',
    'channel',
    'payload',
])]
class Notification extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'payload' => '[]',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_id' => 'string',
            'event_type' => 'string',
            'channel' => 'string',
            'payload' => AsFluent::class,
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Notification $notification) {
            $notification->addStatus(Status::PENDING);
        });
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(NotificationStatus::class);
    }

    public function addStatus(Status $status): void
    {
        $this->statuses()->create(['status' => $status]);
    }
}
