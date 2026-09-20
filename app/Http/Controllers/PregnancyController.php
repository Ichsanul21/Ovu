<?php

namespace App\Http\Controllers;

use App\Services\PregnancyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PregnancyController extends Controller
{
    public function index(Request $request, PregnancyService $service): View
    {
        $user = $request->user();
        $ownerId = $user->visibleUserId();
        $owner = $ownerId === $user->id ? $user : \App\Models\User::findOrFail($ownerId);

        return view('pregnancy.index', [
            'owner' => $owner,
            'status' => $service->status($owner),
            'readonly' => ! $user->isWife(),
        ]);
    }

    public function activate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hpht' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $user = $request->user();
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['pregnancy_start' => $data['hpht'], 'goal' => 'hamil', 'full_name' => $user->profile->full_name ?? $user->name]
        );

        return redirect()->route('pregnancy.index')->with('ok', 'Mode kehamilan aktif. Sehat selalu untuk ibu dan calon buah hati.');
    }

    public function deactivate(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->profile) {
            $user->profile->update(['pregnancy_start' => null, 'goal' => 'kesehatan']);
        }

        return redirect()->route('dashboard')->with('ok', 'Kembali ke mode siklus. Jaga kesehatan selalu ya.');
    }
}
