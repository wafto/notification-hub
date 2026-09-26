<?php

use App\Rules\EventType;

it('passes when the value is a valid UPPER_SNAKE_CASE', function () {
    $rule = new EventType;
    $failed = false;

    $rule->validate('event_type', 'ORDER_CANCELLED', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('fails when the value contains lowercase letter', function () {
    $rule = new EventType;
    $failed = false;

    $rule->validate('event_type', 'order_cancelled', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails when the value contains spaces', function () {
    $rule = new EventType;
    $failed = false;

    $rule->validate('event_type', 'order cancelled', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});
