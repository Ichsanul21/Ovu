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
     * Ambil tips untuk hari ke-N dengan penyesuaian tujuan dan mode remaja.
     * Tujuan: promil, kb, kesehatan, hamil.
     *
     * @return array{fase: string, kondisi: string, tips: array<int, string>}
     */
    public function forDay(int $cycleDay, string $goal = 'kesehatan', bool $teen = false): array
    {
        $day = max(1, min(35, $cycleDay));
        $base = $this->tips[$day] ?? $this->tips[28];

        $tips = $base['tips'];

        if ($teen) {
            $tips[] = $this->teenNote($base['fase']);
        } else {
            $intimacy = $this->intimacyTip($base['fase'], $goal);
            if ($intimacy) {
                $tips[] = $intimacy;
            }
        }

        return [
            'fase' => $base['fase'],
            'kondisi' => $base['kondisi'],
            'tips' => $tips,
        ];
    }

    private function teenNote(string $fase): string
    {
        return match ($fase) {
            'menstruasi' => 'Tahukah kamu: haid adalah cara tubuh melepas dinding rahim yang tidak dipakai. Ganti pembalut tiap 4 sampai 6 jam ya.',
            'folikuler' => 'Tahukah kamu: setelah haid, tubuh mulai menyiapkan sel telur baru. Energi biasanya naik di fase ini.',
            'ovulasi' => 'Tahukah kamu: ovulasi adalah saat sel telur dilepaskan. Bila ada yang membingungkan, tanyakan ke ibu, kakak, atau guru yang kamu percaya.',
            default => 'Tahukah kamu: menjelang haid, mood naik turun itu normal karena hormon. Ceritakan perasaanmu ke orang terdekat.',
        };
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
