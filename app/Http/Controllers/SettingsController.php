<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        return view('settings.index', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'goal' => ['nullable', 'in:promil,kb,kesehatan'],
            'share_sensitive_with_partner' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->update(['name' => $data['name']]);

        if (! empty($data['goal']) && $user->profile) {
            $user->profile->update(['goal' => $data['goal']]);
        }

        $user->update(['share_sensitive_with_partner' => $request->boolean('share_sensitive_with_partner')]);

        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return back()->with('ok', 'Pengaturan tersimpan.');
    }

    public function export(Request $request): StreamedResponse
    {
        $user = $request->user();

        $payload = [
            'user' => $user->only(['name', 'email', 'role']),
            'profile' => $user->profile?->toArray(),
            'cycles' => $user->cycles()->orderBy('start_date')->get()->toArray(),
            'daily_logs' => $user->dailyLogs()->orderBy('log_date')->get()->toArray(),
        ];

        $filename = 'ovu-export-'.now()->format('Ymd').'.json';

        return response()->streamDownload(
            fn () => print(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)),
            $filename,
            ['Content-Type' => 'application/json']
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['confirm' => ['accepted']]);

        $user = $request->user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return redirect()->route('landing')->with('ok', 'Akun dan seluruh datamu sudah dihapus.');
    }

    public function logoutAll(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        Auth::logout();

        return redirect()->route('login')->with('ok', 'Kamu keluar dari perangkat ini. Masuk lagi bila perlu.');
    }
}
