<?php

use App\Rules\PhoneNumber;

it('passes when the value is numeric between 8 to 14 digits', function () {
    $rule = new PhoneNumber;
    $failed = false;

    $rule->validate('phone', '123456789', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('fails when the value is not numeric', function () {
    $rule = new PhoneNumber;
    $failed = false;

    $rule->validate('phone', '123a5678', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails when the value is numeric with more than 14 digits', function () {
    $rule = new PhoneNumber;
    $failed = false;

    $rule->validate('phone', '1234567891201234456', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails when the value is numeric with less than 8 digits', function () {
    $rule = new PhoneNumber;
    $failed = false;

    $rule->validate('phone', '123456', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});
