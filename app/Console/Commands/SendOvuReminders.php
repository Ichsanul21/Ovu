<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\CyclePredictor;
use App\Services\PushService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendOvuReminders extends Command
{
    protected $signature = 'ovu:remind';
    protected $description = 'Kirim pengingat haid, masa subur, keterlambatan, dan BB via push PWA';

    public function handle(CyclePredictor $predictor, PushService $push): int
    {
        $today = Carbon::today();
        $sent = 0;

        foreach (User::where('role', 'wife')->cursor() as $user) {
            $result = $predictor->predict($user);
            if ($result['count'] === 0) {
                continue;
            }

            $messages = [];

            if ($result['next_period'] && $today->diffInDays($result['next_period'], false) === 3) {
                $messages[] = ['Pengingat Ovu', 'Haid diperkirakan 3 hari lagi. Siapkan yang kamu butuhkan ya.'];
            }

            if ($result['fertile_start'] && $today->isSameDay($result['fertile_start'])) {
                $messages[] = ['Pengingat Ovu', 'Masa subur dimulai hari ini.'];
            }

            if ($result['ovulation_date'] && $today->isSameDay($result['ovulation_date'])) {
                $messages[] = ['Pengingat Ovu', 'Perkiraan ovulasi hari ini.'];
            }

            if ($result['late']) {
                $messages[] = ['Pengingat Ovu', 'Haid terlambat dari prediksi. Catat gejala dan pertimbangkan tes bila perlu.'];
            }

            if ($today->day === 1) {
                $messages[] = ['Pengingat Ovu', 'Awal bulan. Yuk update berat badan untuk pantau BMI.'];
            }

            foreach ($messages as [$title, $body]) {
                $sent += $push->sendToUser($user->id, $title, $body);
            }
        }

        $this->info("Reminder terkirim ke {$sent} subscription.");

        return self::SUCCESS;
    }
}
