<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyLogController extends Controller
{
    public function index(Request $request): View
    {
        $ownerId = $request->user()->visibleUserId();
        $date = $request->query('date', Carbon::today()->toDateString());

        $log = DailyLog::where('user_id', $ownerId)->where('log_date', $date)->first();
        $recent = DailyLog::where('user_id', $ownerId)->orderBy('log_date', 'desc')->limit(14)->get();

        return view('logs.index', [
            'date' => $date,
            'log' => $log,
            'recent' => $recent,
            'readonly' => ! $request->user()->isWife(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DailyLog::updateOrCreate(
            ['user_id' => $request->user()->id, 'log_date' => $data['log_date']],
            $data
        );

        if (! empty($data['weight_kg'])) {
            $profile = $request->user()->profile;
            if ($profile) {
                $profile->update(['weight_kg' => $data['weight_kg']]);
            }
        }

        return back()->with('ok', 'Catatan harian tersimpan. Terima kasih sudah merawat diri hari ini.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'log_date' => ['required', 'date', 'before_or_equal:today'],
            'bleeding' => ['nullable', 'integer', 'min:0', 'max:4'],
            'cramp' => ['nullable', 'integer', 'min:1', 'max:5'],
            'headache' => ['nullable', 'integer', 'min:1', 'max:5'],
            'breast_pain' => ['nullable', 'integer', 'min:1', 'max:5'],
            'acne' => ['nullable', 'integer', 'min:1', 'max:5'],
            'nausea' => ['nullable', 'integer', 'min:1', 'max:5'],
            'mood' => ['nullable', 'in:senang,tenang,sensitif,cemas,sedih,marah,lelahan'],
            'energy' => ['nullable', 'integer', 'min:1', 'max:5'],
            'sleep_hours' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'stress' => ['nullable', 'integer', 'min:1', 'max:5'],
            'cervical_fluid' => ['nullable', 'in:kering,lengket,creamy,putih_telur'],
            'bbt' => ['nullable', 'numeric', 'min:34', 'max:40'],
            'lh_test' => ['nullable', 'in:negatif,positif,tidak_tes'],
            'testpack' => ['nullable', 'in:negatif,positif,samar,tidak_tes'],
            'intercourse' => ['nullable', 'boolean'],
            'protected' => ['nullable', 'boolean'],
            'weight_kg' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'symptoms' => ['nullable', 'array'],
            'diary' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['bleeding'] = $validated['bleeding'] ?? 0;
        $validated['intercourse'] = (bool) ($validated['intercourse'] ?? false);
        $validated['protected'] = (bool) ($validated['protected'] ?? false);
        $validated['symptoms'] = isset($validated['symptoms']) ? json_encode($validated['symptoms']) : null;

        return $validated;
    }
}
