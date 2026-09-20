<?php

namespace App\Services;

class CycleTipsService
{
    /** @var array<int, array{fase: string, kondisi: string, tips: array<int, string>}> */
    private array $tips;

    public function __construct()
    {
        $this->tips = require resource_path('data/cycle_tips_id.php');
    }

    /**
     * Ambil tips untuk hari ke-N dengan penyesuaian tujuan.
     * Tujuan: promil, kb, kesehatan.
     *
     * @return array{fase: string, kondisi: string, tips: array<int, string>}
     */
    public function forDay(int $cycleDay, string $goal = 'kesehatan'): array
    {
        $day = max(1, min(35, $cycleDay));
        $base = $this->tips[$day] ?? $this->tips[28];

        $tips = $base['tips'];
        $intimacy = $this->intimacyTip($base['fase'], $goal);
        if ($intimacy) {
            $tips[] = $intimacy;
        }

        return [
            'fase' => $base['fase'],
            'kondisi' => $base['kondisi'],
            'tips' => $tips,
        ];
    }

    private function intimacyTip(string $fase, string $goal): ?string
    {
        if ($fase !== 'ovulasi' && $fase !== 'folikuler') {
            return null;
        }

        if ($goal === 'promil') {
            return 'Untuk program hamil, waktu intim terbaik adalah 2 hari sebelum sampai 1 hari sesudah ovulasi. Jaga suasana tetap santai dan menyenangkan.';
        }

        if ($goal === 'kb') {
            return 'Untuk menunda kehamilan, hindari hubungan tanpa proteksi selama masa subur. Kalender saja tidak 100 persen mencegah, gunakan proteksi bila berhubungan.';
        }

        return 'Bila aktif berhubungan, catat di log harian agar riwayat kesuburanmu lengkap.';
    }
}
