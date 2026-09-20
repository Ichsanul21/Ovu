<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

/** Kartu dialog Asisten Ovu ala Flo. Rule-based dari data, tanpa AI. */
class AssistantService
{
    /**
     * @return array<int, array{title: string, body: string, action_label: string|null, action_route: string|null}>
     */
    public function cards(User $owner, array $prediction, $todayLog = null): array
    {
        $cards = [];
        $today = Carbon::today();
        $goal = $owner->profile->goal ?? 'kesehatan';
        $teen = (bool) ($owner->profile->is_teen ?? false);

        if ($prediction['count'] === 0) {
            $cards[] = [
                'title' => $teen ? 'Halo, salam kenal' : 'Halo, mulai dari sini yuk',
                'body' => $teen
                    ? 'Catat tanggal haid terakhirmu. Tenang saja, semua bisa dipelajari pelan-pelan dan datamu aman.'
                    : 'Catat tanggal haid terakhirmu agar aku bisa membuat prediksi siklus untukmu.',
                'action_label' => 'Catat haid',
                'action_route' => 'cycles.index',
            ];

            return $cards;
        }

        if ($prediction['count'] < 3) {
            $cards[] = [
                'title' => 'Prediksimu makin pintar',
                'body' => 'Tambahkan haid 2 sampai 3 siklus terakhir agar prediksi makin akurat. Dari ingatan pun boleh.',
                'action_label' => 'Tambah riwayat',
                'action_route' => 'cycles.index',
            ];
        }

        if ($todayLog && ($todayLog->cramp ?? 0) >= 4) {
            $cards[] = [
                'title' => 'Kram berat hari ini',
                'body' => $teen
                    ? 'Kompres hangat dan istirahat bisa membantu. Ceritakan ke ibu atau kakak bila nyerinya mengganggu aktivitas.'
                    : 'Kompres hangat, istirahat, dan pereda nyeri sesuai anjuran bisa membantu. Bila tiap bulan mengganggu aktivitas, konsultasikan ke dokter.',
                'action_label' => null,
                'action_route' => null,
            ];
        }

        $inFertile = $prediction['fertile_start'] && $prediction['fertile_end']
            && $today->between($prediction['fertile_start'], $prediction['fertile_end']);

        if ($inFertile && ! $teen) {
            $cards[] = [
                'title' => 'Kamu sedang di masa subur',
                'body' => match ($goal) {
                    'promil' => 'Waktu terbaik untuk program hamil adalah 2 hari sebelum sampai 1 hari sesudah ovulasi. Jaga suasana tetap santai.',
                    'kb' => 'Untuk menunda kehamilan, hindari hubungan tanpa proteksi selama masa subur. Kalender saja tidak 100 persen mencegah.',
                    default => 'Tubuhmu sedang di masa paling subur bulan ini. Catat lendir serviks agar polanya terbaca.',
                },
                'action_label' => 'Catat gejala',
                'action_route' => 'logs.index',
            ];
        }

        if ($prediction['late']) {
            $cards[] = [
                'title' => 'Haidmu terlambat',
                'body' => $teen
                    ? 'Terlambat beberapa hari itu umum, apalagi di awal-awal haid. Tetap catat gejala dan ceritakan ke orang dewasa yang kamu percaya bila khawatir.'
                    : 'Sudah lewat 3 hari dari prediksi. Bila aktif berhubungan tanpa proteksi, pertimbangkan testpack pagi hari.',
                'action_label' => 'Catat gejala',
                'action_route' => 'logs.index',
            ];
        }

        if ($todayLog && ($todayLog->testpack ?? 'tidak_tes') === 'positif' && $goal !== 'hamil') {
            $cards[] = [
                'title' => 'Testpack positif?',
                'body' => 'Selamat. Kamu bisa mengaktifkan mode kehamilan untuk panduan mingguan yang lembut.',
                'action_label' => 'Aktifkan mode hamil',
                'action_route' => 'pregnancy.index',
            ];
        }

        return array_slice($cards, 0, 3);
    }
}
