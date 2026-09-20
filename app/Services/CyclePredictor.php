<?php

namespace App\Services;

use App\Models\Cycle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CyclePredictor
{
    /**
     * Hitung prediksi dari riwayat siklus.
     * Mengembalikan array dengan kunci:
     * next_period, ovulation_date, fertile_start, fertile_end,
     * confidence, avg_cycle, late (bool), cycle_day (int|null).
     */
    public function predict(User $user, ?Carbon $today = null): array
    {
        $today = ($today ?? now())->copy()->startOfDay();

        /** @var Collection<int, Cycle> $cycles */
        $cycles = $user->cycles()->orderBy('start_date')->get();

        if ($cycles->isEmpty()) {
            return $this->emptyResult();
        }

        $lengths = collect();
        $ordered = $cycles->values();
        for ($i = 0; $i < $ordered->count() - 1; $i++) {
            $len = $ordered[$i]->start_date->diffInDays($ordered[$i + 1]->start_date);
            if ($len >= 21 && $len <= 45) {
                $lengths->push($len);
            }
        }

        $lastStart = $cycles->last()->start_date->copy()->startOfDay();
        $avg = $lengths->isNotEmpty() ? round($lengths->avg(), 1) : 28.0;

        $nextPeriod = $lastStart->copy()->addDays((int) round($avg));
        $ovulation = $nextPeriod->copy()->subDays(14);
        $fertileStart = $ovulation->copy()->subDays(5);
        $fertileEnd = $ovulation->copy()->addDay();

        $confidence = $this->confidence($lengths);
        $cycleDay = $lastStart->diffInDays($today) + 1;
        if ($cycleDay < 1 || $cycleDay > 60) {
            $cycleDay = null;
        }

        return [
            'next_period' => $nextPeriod,
            'ovulation_date' => $ovulation,
            'fertile_start' => $fertileStart,
            'fertile_end' => $fertileEnd,
            'confidence' => $confidence,
            'avg_cycle' => $avg,
            'late' => $today->gt($nextPeriod->copy()->addDays(3)),
            'cycle_day' => $cycleDay,
            'count' => $cycles->count(),
        ];
    }

    public function phase(?int $cycleDay, float $avgCycle = 28): string
    {
        if ($cycleDay === null) {
            return 'unknown';
        }

        if ($cycleDay <= 5) {
            return 'menstruasi';
        }

        $ovulationDay = (int) round($avgCycle) - 14;

        if ($cycleDay < $ovulationDay - 2) {
            return 'folikuler';
        }

        if ($cycleDay <= $ovulationDay + 1) {
            return 'ovulasi';
        }

        return 'luteal';
    }

    /** @param Collection<int, int> $lengths */
    private function confidence(Collection $lengths): string
    {
        if ($lengths->count() < 3) {
            return 'Rendah';
        }

        $spread = $lengths->max() - $lengths->min();

        if ($spread <= 3) {
            return 'Tinggi';
        }

        if ($spread <= 7) {
            return 'Sedang';
        }

        return 'Rendah';
    }

    private function emptyResult(): array
    {
        return [
            'next_period' => null,
            'ovulation_date' => null,
            'fertile_start' => null,
            'fertile_end' => null,
            'confidence' => 'Rendah',
            'avg_cycle' => 28.0,
            'late' => false,
            'cycle_day' => null,
            'count' => 0,
        ];
    }
}
