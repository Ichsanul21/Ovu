<?php

namespace App\Http\Controllers;

use App\Services\CyclePredictor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request, CyclePredictor $predictor): View
    {
        $ownerId = $request->user()->visibleUserId();
        $owner = \App\Models\User::findOrFail($ownerId);

        $month = $request->query('month', Carbon::today()->format('Y-m'));
        try {
            $cursor = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $cursor = Carbon::today()->startOfMonth();
        }

        $maxFuture = Carbon::today()->addYears(5)->startOfMonth();
        if ($cursor->gt($maxFuture)) {
            $cursor = $maxFuture->copy();
        }

        $prediction = $predictor->predict($owner);
        $projection = $predictor->project($owner, 5);
        $cycles = $owner->cycles()->orderBy('start_date')->get();
        $logs = $owner->dailyLogs()
            ->whereBetween('log_date', [$cursor->copy()->startOfMonth()->subDays(7), $cursor->copy()->endOfMonth()->addDays(7)])
            ->get()->keyBy(fn ($l) => $l->log_date->toDateString());

        $cells = [];
        $start = $cursor->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end = $cursor->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            [$kind, $projected] = $this->classify($d, $cycles, $prediction, $projection);
            $cells[] = [
                'date' => $d->copy(),
                'in_month' => $d->month === $cursor->month,
                'is_today' => $d->isToday(),
                'kind' => $kind,
                'projected' => $projected,
                'has_log' => $logs->has($key),
            ];
        }

        return view('calendar.index', [
            'cursor' => $cursor,
            'cells' => $cells,
            'prediction' => $prediction,
            'edit' => $request->boolean('edit'),
            'maxFuture' => $maxFuture,
            'readonly' => ! $request->user()->isWife(),
        ]);
    }

    /**
     * Klasifikasi Flo: haid | prediksi_haid | ovulasi | subur | luteal | biasa.
     *
     * @return array{0: string, 1: bool}
     */
    private function classify(Carbon $day, $cycles, array $prediction, array $projection): array
    {
        foreach ($cycles as $cycle) {
            $start = $cycle->start_date;
            $end = $cycle->end_date ?? $start;
            if ($day->between($start, $end)) {
                return ['haid', false];
            }
        }

        if (! empty($prediction['next_period']) && $day->isSameDay($prediction['next_period'])) {
            return ['prediksi_haid', true];
        }

        if (! empty($prediction['ovulation_date']) && $day->isSameDay($prediction['ovulation_date'])) {
            return ['ovulasi', true];
        }

        if (! empty($prediction['fertile_start']) && ! empty($prediction['fertile_end'])
            && $day->between($prediction['fertile_start'], $prediction['fertile_end'])) {
            return ['subur', true];
        }

        if (! empty($prediction['next_period']) && ! empty($prediction['ovulation_date'])
            && $day->gt($prediction['ovulation_date']) && $day->lt($prediction['next_period'])) {
            return ['luteal', true];
        }

        foreach ($projection as $p) {
            if ($day->isSameDay($p['start'])) {
                return ['prediksi_haid', true];
            }
            if ($day->isSameDay($p['ovulation'])) {
                return ['ovulasi', true];
            }
            if ($day->between($p['fertile_start'], $p['fertile_end'])) {
                return ['subur', true];
            }
        }

        return ['biasa', false];
    }
}
