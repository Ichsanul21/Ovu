<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Services\CyclePredictor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CycleController extends Controller
{
    public function index(Request $request, CyclePredictor $predictor): View
    {
        $ownerId = $request->user()->visibleUserId();
        $cycles = Cycle::where('user_id', $ownerId)->orderBy('start_date', 'desc')->get();
        $owner = \App\Models\User::find($ownerId);
        $prediction = $predictor->predict($owner);

        return view('cycles.index', compact('cycles', 'prediction'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'start_date' => ['required', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();

        Cycle::updateOrCreate(
            ['user_id' => $user->id, 'start_date' => $data['start_date']],
            ['end_date' => $data['end_date'] ?? null, 'notes' => $data['notes'] ?? null]
        );

        $this->recalcLengths($user);
        $this->cachePrediction($user);

        return back()->with('ok', 'Siklus tersimpan. Prediksi diperbarui.');
    }

    public function quickToday(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $exists = Cycle::where('user_id', $user->id)
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })->exists();

        if (! $exists) {
            Cycle::create(['user_id' => $user->id, 'start_date' => $today, 'end_date' => $today]);
            $this->recalcLengths($user);
            $this->cachePrediction($user);
        }

        return back()->with('ok', 'Haid hari ini dicatat.');
    }

    public function destroy(Request $request, Cycle $cycle): RedirectResponse
    {
        abort_unless($cycle->user_id === $request->user()->id, 403);
        $cycle->delete();

        $this->recalcLengths($request->user());
        $this->cachePrediction($request->user());

        return back()->with('ok', 'Siklus dihapus.');
    }

    private function recalcLengths(\App\Models\User $user): void
    {
        $cycles = $user->cycles()->orderBy('start_date')->get();
        foreach ($cycles as $i => $cycle) {
            $next = $cycles->get($i + 1);
            $cycle->cycle_length = $next
                ? $cycle->start_date->diffInDays($next->start_date)
                : null;
            $cycle->saveQuietly();
        }
    }

    private function cachePrediction(\App\Models\User $user): void
    {
        $result = app(CyclePredictor::class)->predict($user);

        $user->prediction()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'next_period' => $result['next_period']?->toDateString(),
                'ovulation_date' => $result['ovulation_date']?->toDateString(),
                'fertile_start' => $result['fertile_start']?->toDateString(),
                'fertile_end' => $result['fertile_end']?->toDateString(),
                'confidence' => $result['confidence'],
                'avg_cycle' => $result['avg_cycle'],
                'generated_at' => now(),
            ]
        );
    }
}
