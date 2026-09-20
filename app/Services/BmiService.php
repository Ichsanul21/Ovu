<?php

namespace App\Services;

class BmiService
{
    public function bmi(?float $weightKg, ?int $heightCm): ?float
    {
        if (! $weightKg || ! $heightCm || $heightCm <= 0) {
            return null;
        }

        $m = $heightCm / 100;

        return round($weightKg / ($m * $m), 1);
    }

    public function category(?float $bmi): ?string
    {
        if ($bmi === null) {
            return null;
        }

        if ($bmi < 18.5) {
            return 'Kurus';
        }

        if ($bmi <= 22.9) {
            return 'Normal';
        }

        if ($bmi <= 24.9) {
            return 'Gemuk ringan';
        }

        return 'Obesitas';
    }

    public function note(?float $bmi): ?string
    {
        if ($bmi === null) {
            return null;
        }

        if ($bmi < 18.5) {
            return 'Berat badan rendah kadang membuat siklus memanjang atau haid tidak teratur. Pantau pola makan dan catat perubahan siklus.';
        }

        if ($bmi > 24.9) {
            return 'Berat badan berlebih kadang berkaitan dengan siklus memanjang. Aktivitas ringan rutin dan tidur cukup membantu keteraturan.';
        }

        return 'BMI kamu dalam rentang normal. Pertahankan pola makan seimbang dan aktivitas rutin.';
    }
}
