<?php

namespace App\Http\Controllers;

use App\Models\PartnerInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $invites = $request->user()->partnerInvites()->orderBy('created_at', 'desc')->limit(5)->get();

        return view('partner.index', [
            'invites' => $invites,
            'shareSensitive' => (bool) $request->user()->share_sensitive_with_partner,
        ]);
    }

    public function createInvite(Request $request): RedirectResponse
    {
        $code = strtoupper(Str::random(6));

        PartnerInvite::create([
            'user_id' => $request->user()->id,
            'code' => $code,
            'expires_at' => now()->addDay(),
        ]);

        return back()->with('ok', 'Kode undangan dibuat: '.$code.'. Berlaku 24 jam.');
    }

    public function toggleShare(Request $request): RedirectResponse
    {
        $request->validate(['share_sensitive' => ['nullable', 'boolean']]);

        $request->user()->update([
            'share_sensitive_with_partner' => $request->boolean('share_sensitive'),
        ]);

        return back()->with('ok', 'Pengaturan berbagi diperbarui.');
    }

    public function revoke(Request $request): RedirectResponse
    {
        $request->user()->update(['partner_code' => null]);
        PartnerInvite::where('user_id', $request->user()->id)->delete();

        return back()->with('ok', 'Akses pasangan dicabut. Pasangan perlu kode baru untuk melihat lagi.');
    }
}
