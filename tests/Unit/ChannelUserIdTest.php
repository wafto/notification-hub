<?php

use App\Rules\ChannelUserId;

it('passes when the value is a valid user_id', function () {
    $rule = new ChannelUserId;
    $failed = false;

    $rule->validate('event_type', '12345', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('passes when the value is not a valid user_id', function () {
    $rule = new ChannelUserId;
    $failed = false;

    $rule->validate('event_type', '1a234e5', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});