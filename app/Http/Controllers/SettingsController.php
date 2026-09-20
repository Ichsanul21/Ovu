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
            'goal' => ['nullable', 'in:promil,kb,kesehatan,hamil'],
            'share_sensitive_with_partner' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'strip_days' => ['nullable', 'in:7,14'],
            'visible_categories' => ['nullable', 'array'],
            'kb_pill_time' => ['nullable', 'date_format:H:i'],
            'kb_pill_active' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $user->update(['name' => $data['name']]);

        if ($user->profile) {
            $profileData = [];
            if (! empty($data['goal'])) {
                $profileData['goal'] = $data['goal'];
            }
            if (! empty($data['strip_days'])) {
                $profileData['strip_days'] = (int) $data['strip_days'];
            }
            if (isset($data['visible_categories'])) {
                $profileData['visible_categories'] = json_encode(array_values($data['visible_categories']));
            }
            if (isset($data['kb_pill_time'])) {
                $profileData['kb_pill_time'] = $data['kb_pill_time'];
            }
            $profileData['kb_pill_active'] = $request->boolean('kb_pill_active');
            if (! empty($profileData)) {
                $user->profile->update($profileData);
            }
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
