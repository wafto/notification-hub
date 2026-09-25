<?php

namespace App\Providers;

use App\Channels\Channel;
use App\Channels\Resolver;
use App\Channels\Services\EmailChannel;
use App\Channels\Services\SmsChannel;
use Illuminate\Support\ServiceProvider;

class ChannelsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->when(Resolver::class)
            ->needs(Channel::class)
            ->give([
                EmailChannel::class,
                SmsChannel::class,
            ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
