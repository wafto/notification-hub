<?php

use App\Models\Notification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'notifications' => Notification::query()->with('statuses', 'status')->get(),
    ]);
});
