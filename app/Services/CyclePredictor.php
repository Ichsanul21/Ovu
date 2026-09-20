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
        $typical = (int) ($user->profile->typical_cycle_length ?? 28);
        $typical = max(21, min(45, $typical));

        if ($lengths->isNotEmpty()) {
            $blended = ($lengths->sum() + $typical) / ($lengths->count() + 1);
            $avg = round($blended, 1);
        } else {
            $avg = (float) $typical;
        }

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

    /**
     * Proyeksi rolling sampai 5 tahun ke depan, berjangkar tanggal haid asli terakhir.
     * Tiap ada data real baru, jangkar pindah sehingga proyeksi selalu menyesuaikan.
     *
     * @return array<int, array{start: Carbon, ovulation: Carbon, fertile_start: Carbon, fertile_end: Carbon}>
     */
    public function project(User $user, int $years = 5): array
    {
        $cycles = $user->cycles()->orderBy('start_date')->get();

        if ($cycles->isEmpty()) {
            return [];
        }

        $result = $this->predict($user);
        $avgDays = max(21, min(45, (int) round($result['avg_cycle'])));
        $anchor = $cycles->last()->start_date->copy()->startOfDay();
        $limit = $anchor->copy()->addYears($years);

        $out = [];
        $cursor = $anchor->copy();
        while (true) {
            $cursor = $cursor->copy()->addDays($avgDays);
            if ($cursor->gt($limit) || count($out) >= 100) {
                break;
            }
            $ovu = $cursor->copy()->subDays(14);
            $out[] = [
                'start' => $cursor->copy(),
                'ovulation' => $ovu,
                'fertile_start' => $ovu->copy()->subDays(5),
                'fertile_end' => $ovu->copy()->addDay(),
            ];
        }

        return $out;
    }

    /** Peluang hamil hari ini: label dan persen ala Flo. */
    public function pregnancyChance(?Carbon $today, ?Carbon $ovulation, ?Carbon $fertileStart, ?Carbon $fertileEnd): array
    {
        if (! $today || ! $ovulation || ! $fertileStart || ! $fertileEnd) {
            return ['label' => 'Belum tahu', 'percent' => 0];
        }

        $d = $today->copy()->startOfDay()->diffInDays($ovulation->copy()->startOfDay(), false);

        if ($d >= -1 && $d <= 1) {
            return ['label' => 'Puncak', 'percent' => 32];
        }

        if ($today->between($fertileStart, $fertileEnd)) {
            return ['label' => 'Tinggi', 'percent' => 24];
        }

        if ($d >= -5 && $d < -1) {
            return ['label' => 'Sedang', 'percent' => 12];
        }

        return ['label' => 'Rendah', 'percent' => 3];
    }

    /** Hitung mundur: ke ovulasi dulu, sesudah ovulasi ke haid. */
    public function countdown(?Carbon $today, ?Carbon $ovulation, ?Carbon $nextPeriod): array
    {
        if (! $today || ! $ovulation || ! $nextPeriod) {
            return ['label' => 'Catat haid dulu yuk', 'days' => null, 'target' => null];
        }

        $today = $today->copy()->startOfDay();

        if ($today->lte($ovulation)) {
            $days = $today->diffInDays($ovulation);

            return ['label' => $days === 0 ? 'Ovulasi hari ini' : "Ovulasi {$days} hari lagi", 'days' => $days, 'target' => 'ovulasi'];
        }

        if ($today->lte($nextPeriod)) {
            $days = $today->diffInDays($nextPeriod);

            return ['label' => $days === 0 ? 'Haid hari ini' : "Haid {$days} hari lagi", 'days' => $days, 'target' => 'haid'];
        }

        $late = $nextPeriod->diffInDays($today);

        return ['label' => "Terlambat {$late} hari", 'days' => $late, 'target' => 'telat'];
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
