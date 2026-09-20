<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PushController extends Controller
{
    public function vapidKey(): JsonResponse
    {
        return response()->json(['key' => config('services.vapid.public_key')]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
        ]);

        $request->user()->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $data['endpoint']],
            ['p256dh' => $data['keys']['p256dh'], 'auth_token' => $data['keys']['auth']]
        );

        return response()->json(['ok' => true]);
    }

    public function settings(Request $request): View
    {
        return view('push.settings');
    }
}
