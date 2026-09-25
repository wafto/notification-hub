<?php

namespace App\Http\Controllers\API\V1;

use App\Channels\Dispatch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function store(Request $request, Dispatch $dispatch): JsonResponse
    {
        $dispatch($request->all());

        return response()->json([
            'event_id' => $request->input('event_id'),
            'event_type' => $request->input('event_type'),
        ], 201);
    }
}
