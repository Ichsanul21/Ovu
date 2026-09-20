<?php

namespace App\Services;

use App\Models\User;

class InsightEngine
{
    /**
     * Hasilkan insight pola dari data user.
     * Setiap insight: ['level' => info|waspada, 'title' => ..., 'body' => ...]
     * Selalu sertakan disclaimer medis di akhir daftar.
     *
     * @return array<int, array{level: string, title: string, body: string}>
     */
    public function build(User $user): array
    {
        $insights = [];

        $cycles = $user->cycles()->orderBy('start_date')->get();
        $logs = $user->dailyLogs()->orderBy('log_date')->get();
        $conditions = $user->profile?->conditionsList() ?? [];

        if ($cycles->count() >= 2) {
            $ordered = $cycles->values();
            $lengths = collect();
            for ($i = 0; $i < $ordered->count() - 1; $i++) {
                $lengths->push($ordered[$i]->start_date->diffInDays($ordered[$i + 1]->start_date));
            }

            $long = $lengths->filter(fn ($l) => $l > 35)->count();
            if ($long >= 2) {
                $insights[] = [
                    'level' => in_array('PCOS', $conditions) ? 'waspada' : 'info',
                    'title' => 'Siklus sering lebih dari 35 hari',
                    'body' => 'Dua siklus terakhir atau lebih berjarak di atas 35 hari. Pola ini umum pada PCOS atau setelah perubahan berat badan dan stres. Bawa catatan ini saat konsultasi ke dokter bila berlanjut.',
                ];
            }

            $spread = $lengths->max() - $lengths->min();
            if ($lengths->count() >= 3 && $spread > 7) {
                $insights[] = [
                    'level' => 'info',
                    'title' => 'Panjang siklus cukup bervariasi',
                    'body' => 'Selisih siklus terpendek dan terpanjang lebih dari 7 hari. Coba catat tidur, stres, dan perubahan berat badan untuk melihat pemicunya.',
                ];
            }
        }

        $heavyCramps = $logs->where('cramp', '>=', 4)->count();
        if ($heavyCramps >= 3) {
            $insights[] = [
                'level' => in_array('Endometriosis', $conditions) ? 'waspada' : 'info',
                'title' => 'Kram berat berulang',
                'body' => 'Kram level 4 sampai 5 tercatat beberapa kali. Kompres hangat, istirahat, dan pereda nyeri sesuai anjuran apoteker bisa membantu. Bila nyeri mengganggu aktivitas setiap bulan, konsultasikan ke dokter.',
            ];
        }

        $lowEnergy = $logs->where('energy', '<=', 2)->count();
        if ($lowEnergy >= 5) {
            $insights[] = [
                'level' => 'info',
                'title' => 'Energi rendah cukup sering',
                'body' => 'Energi rendah tercatat berulang. Perhatikan jam tidur, asupan zat besi saat haid, dan jeda istirahat di siang hari.',
            ];
        }

        $pms = $logs->filter(fn ($l) => in_array($l->mood, ['sedih', 'cemas', 'marah', 'sensitif']))->count();
        if ($pms >= 5) {
            $insights[] = [
                'level' => 'info',
                'title' => 'Mood sensitif berulang',
                'body' => 'Mood sedih, cemas, atau sensitif muncul beberapa kali dalam catatan. Fase luteal memang sering memengaruhi perasaan. Ceritakan ke orang terdekat dan jaga waktu tidur.',
            ];
        }

        $insights[] = [
            'level' => 'info',
            'title' => 'Catatan penting',
            'body' => 'Insight ini adalah pola statistik dari catatanmu, bukan diagnosis medis. Untuk keluhan yang menetap, bawa laporan Ovu ke dokter atau bidan.',
        ];

        return $insights;
    }
}
