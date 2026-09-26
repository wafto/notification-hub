<?php

use App\Channels\Channel;
use App\Channels\Resolver;
use App\Channels\Services\EmailChannel;
use App\Channels\Services\SmsChannel;
use App\Models\Notification;
use App\Rules\ChannelAvailable;

$resolver = new Resolver(new EmailChannel, new SmsChannel);

$blockachain = new class implements Channel
{
    public function name(): string
    {
        return 'blockachain';
    }

    public function rules(): array
    {
        return [];
    }

    public function send(Notification $notification): bool
    {
        return true;
    }
};

it('passes when the value contains only available channels', function () use ($resolver) {
    $rule = new ChannelAvailable($resolver);
    $failed = false;

    $rule->validate('channels', ['sms', 'email'], function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('fails when the value contains duplicated available channels', function () use ($resolver) {
    $rule = new ChannelAvailable($resolver);
    $failed = false;

    $rule->validate('channels', ['sms', 'email', 'sms'], function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails when the value contains unexisting channels', function () use ($resolver) {
    $rule = new ChannelAvailable($resolver);
    $failed = false;

    $rule->validate('channels', ['sms', 'email', 'blockachain'], function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('passes when we add a new channel.', function () use ($blockachain) {
    $resolver = new Resolver(new EmailChannel, new SmsChannel, $blockachain);
    $rule = new ChannelAvailable($resolver);
    $failed = false;

    $rule->validate('channels', ['sms', 'email', 'blockachain'], function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});