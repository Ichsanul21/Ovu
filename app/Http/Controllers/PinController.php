<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PinController extends Controller
{
    public function show(): View
    {
        return view('auth.pin');
    }

    public function unlock(Request $request): RedirectResponse
    {
        $request->validate(['pin' => ['required', 'digits:4']]);

        $user = $request->user();
        if (! $user->pin_code || ! Hash::check($request->input('pin'), $user->pin_code)) {
            return back()->withErrors(['pin' => 'PIN salah.']);
        }

        $request->session()->put('unlocked', true);

        return redirect()->intended(route('dashboard'));
    }

    public function lock(Request $request): RedirectResponse
    {
        $request->session()->forget('unlocked');

        return redirect()->route('pin.show');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'pin' => ['nullable', 'digits:4'],
            'current_password' => ['required', 'current_password'],
        ]);

        $request->user()->update([
            'pin_code' => $request->input('pin') ? Hash::make($request->input('pin')) : null,
        ]);

        return back()->with('ok', $request->input('pin') ? 'Kunci PIN aktif.' : 'Kunci PIN dimatikan.');
    }
}
