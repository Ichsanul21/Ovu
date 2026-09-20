<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function showOnboarding(Request $request): View
    {
        return view('profile.onboarding', ['profile' => $request->user()->profile]);
    }

    public function storeOnboarding(Request $request): RedirectResponse
    {
        $data = $this->validateProfile($request);
        $data['user_id'] = $request->user()->id;

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        $this->refreshPrediction($request);

        return redirect()->route('cycles.index')->with('ok', 'Profil tersimpan. Sekarang masukkan riwayat haidmu ya.');
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', ['profile' => $request->user()->profile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $this->validateProfile($request);

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return back()->with('ok', 'Profil diperbarui.');
    }

    /** @return array<string, mixed> */
    private function validateProfile(Request $request): array
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'height_cm' => ['nullable', 'integer', 'min:100', 'max:250'],
            'weight_kg' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'conditions' => ['nullable', 'array'],
            'conditions.*' => ['string', 'max:50'],
            'conditions_other' => ['nullable', 'string', 'max:150'],
            'routine_meds' => ['nullable', 'string', 'max:255'],
            'kb_history' => ['nullable', 'string', 'max:50'],
            'pregnancy_history' => ['nullable', 'string', 'max:100'],
            'goal' => ['required', 'in:promil,kb,kesehatan'],
        ]);

        $conditions = $validated['conditions'] ?? [];
        if (! empty($validated['conditions_other'])) {
            $conditions[] = 'Lainnya: '.$validated['conditions_other'];
        }
        unset($validated['conditions_other']);
        $validated['conditions'] = json_encode(array_values($conditions));

        return $validated;
    }

    private function refreshPrediction(Request $request): void
    {
        app(\App\Services\CyclePredictor::class);
    }
}
