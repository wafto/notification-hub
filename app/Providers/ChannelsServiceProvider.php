<?php

namespace App\Providers;

use App\Channels\Channel;
use App\Channels\ChannelsResolver;
use App\Channels\EmailChannel;
use App\Channels\SmsChannel;
use Illuminate\Support\ServiceProvider;

class ChannelsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->when(ChannelsResolver::class)
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
