<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'in:wife,husband'],
            'partner_code' => ['nullable', 'string', 'max:10'],
            'consent_zero_use' => ['accepted'],
            'consent_owner_only' => ['accepted'],
        ], [
            'consent_zero_use.accepted' => 'Centang persetujuan kebijakan privasi dulu ya.',
            'consent_owner_only.accepted' => 'Centang persetujuan akses pemilik akun dulu ya.',
        ]);

        if (($data['role'] ?? 'wife') === 'husband' && empty($data['partner_code'])) {
            return back()->withErrors(['partner_code' => 'Akun pasangan butuh kode undangan dari istri.'])->withInput();
        }

        if (! empty($data['partner_code'])) {
            $invite = \App\Models\PartnerInvite::where('code', $data['partner_code'])->first();
            if (! $invite || ! $invite->isValid()) {
                return back()->withErrors(['partner_code' => 'Kode undangan tidak valid atau kedaluwarsa.'])->withInput();
            }
            $invite->update(['used_at' => now()]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'wife',
            'partner_code' => $data['partner_code'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isWife()) {
            return redirect()->route('onboarding.show');
        }

        return redirect()->route('dashboard');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember', false);

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        $request->session()->regenerate();

        $user = $request->user();
        if ($user->isWife() && ! $user->profile) {
            return redirect()->route('onboarding.show');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
