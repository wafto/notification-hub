<?php

use App\Http\Controllers\API\V1\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::name('v1.')
    ->prefix('v1')
    ->group(function () {
        /** Notification store */
        Route::post('notifications', [NotificationsController::class, 'store'])->name('notifications.store');
    });
