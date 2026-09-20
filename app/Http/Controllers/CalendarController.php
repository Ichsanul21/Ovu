<?php

namespace App\Http\Controllers;

use App\Services\CyclePredictor;
use App\Services\InsightEngine;
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

        $prediction = $predictor->predict($owner);
        $cycles = $owner->cycles()->orderBy('start_date')->get();
        $logs = $owner->dailyLogs()
            ->whereBetween('log_date', [$cursor->copy()->startOfMonth()->subDays(7), $cursor->copy()->endOfMonth()->addDays(7)])
            ->get()->keyBy(fn ($l) => $l->log_date->toDateString());

        $cells = [];
        $start = $cursor->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end = $cursor->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            $cells[] = [
                'date' => $d->copy(),
                'in_month' => $d->month === $cursor->month,
                'is_today' => $d->isToday(),
                'kind' => $this->classify($d, $cycles, $prediction),
                'has_log' => $logs->has($key),
            ];
        }

        return view('calendar.index', [
            'cursor' => $cursor,
            'cells' => $cells,
            'prediction' => $prediction,
            'readonly' => ! $request->user()->isWife(),
        ]);
    }

    /** @param \Illuminate\Support\Collection<int, \App\Models\Cycle> $cycles */
    private function classify(Carbon $day, $cycles, array $prediction): string
    {
        foreach ($cycles as $cycle) {
            $start = $cycle->start_date;
            $end = $cycle->end_date ?? $start;
            if ($day->between($start, $end)) {
                return 'haid';
            }
        }

        foreach (['next_period'] as $k) {
            if (! empty($prediction[$k]) && $day->isSameDay($prediction[$k])) {
                return 'prediksi_haid';
            }
        }

        if (! empty($prediction['ovulation_date']) && $day->isSameDay($prediction['ovulation_date'])) {
            return 'ovulasi';
        }

        if (! empty($prediction['fertile_start']) && ! empty($prediction['fertile_end'])
            && $day->between($prediction['fertile_start'], $prediction['fertile_end'])) {
            return 'subur';
        }

        return 'biasa';
    }
}
