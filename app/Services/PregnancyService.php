<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class PregnancyService
{
    public function status(User $owner): ?array
    {
        $start = $owner->profile->pregnancy_start ?? null;
        if (! $start) {
            return null;
        }

        $start = $start->copy()->startOfDay();
        $today = Carbon::today();
        $days = $start->diffInDays($today);
        $weeks = intdiv($days, 7);
        $rest = $days % 7;
        $due = $start->copy()->addDays(280);

        $trimester = $weeks < 13 ? 1 : ($weeks < 28 ? 2 : 3);

        return [
            'hpht' => $start,
            'weeks' => $weeks,
            'days_rest' => $rest,
            'due' => $due,
            'trimester' => $trimester,
            'checklist' => $this->checklist($trimester),
        ];
    }

    /** @return array<int, string> */
    private function checklist(int $trimester): array
    {
        return match ($trimester) {
            1 => [
                'Minum asam folat sesuai anjuran dokter atau bidan.',
                'Jadwalkan kontrol kehamilan pertama.',
                'Hindari rokok, alkohol, dan obat tanpa resep dokter.',
                'Istirahat cukup dan makan bergizi seimbang.',
            ],
            2 => [
                'Rutin kontrol sesuai jadwal (biasanya sebulan sekali).',
                'Perhatikan gerakan janin mulai terasa.',
                'Jaga asupan zat besi dan kalsium.',
                'Olahraga ringan seperti jalan santai bila diizinkan.',
            ],
            default => [
                'Siapkan tas bersalin dan dokumen penting.',
                'Kenali tanda persalinan dan nomor darurat.',
                'Kontrol lebih sering sesuai anjuran (seminggu sekali).',
                'Istirahat cukup, minta bantuan untuk pekerjaan berat.',
            ],
        };
    }
}
