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
        $data = $this->validateProfile($request, true);
        $lastPeriod = $data['last_period'] ?? null;
        unset($data['last_period']);

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        if ($lastPeriod && $request->user()->isWife()) {
            \App\Models\Cycle::updateOrCreate(
                ['user_id' => $request->user()->id, 'start_date' => $lastPeriod],
                ['end_date' => \Carbon\Carbon::parse($lastPeriod)->addDays(max(0, ($data['typical_period_length'] ?? 5) - 1))->toDateString()]
            );
        }

        return redirect()->route('dashboard')->with('ok', 'Selamat datang di Ovu. Prediksi pertamamu sudah jadi.');
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
    private function validateProfile(Request $request, bool $wizard = false): array
    {
        $rules = [
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
            'goal' => ['required', 'in:promil,kb,kesehatan,hamil'],
            'typical_cycle_length' => ['nullable', 'integer', 'min:21', 'max:45'],
            'typical_period_length' => ['nullable', 'integer', 'min:1', 'max:10'],
            'is_teen' => ['nullable', 'boolean'],
            'strip_days' => ['nullable', 'in:7,14'],
            'visible_categories' => ['nullable', 'array'],
            'kb_pill_time' => ['nullable', 'date_format:H:i'],
            'kb_pill_active' => ['nullable', 'boolean'],
        ];

        if ($wizard) {
            $rules['last_period'] = ['nullable', 'date', 'before_or_equal:today'];
        }

        $validated = $request->validate($rules);

        $conditions = $validated['conditions'] ?? [];
        if (! empty($validated['conditions_other'])) {
            $conditions[] = 'Lainnya: '.$validated['conditions_other'];
        }
        unset($validated['conditions_other']);
        $validated['conditions'] = json_encode(array_values($conditions));

        $validated['is_teen'] = (bool) ($validated['is_teen'] ?? false);
        $validated['kb_pill_active'] = (bool) ($validated['kb_pill_active'] ?? false);
        if (isset($validated['visible_categories'])) {
            $validated['visible_categories'] = json_encode(array_values($validated['visible_categories']));
        }

        return $validated;
    }
}
