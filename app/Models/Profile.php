<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'birth_date', 'height_cm', 'weight_kg',
        'conditions', 'routine_meds', 'kb_history', 'pregnancy_history', 'goal',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'weight_kg' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function age(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    public function conditionsList(): array
    {
        if (! $this->conditions) {
            return [];
        }

        $decoded = json_decode($this->conditions, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function zodiac(): ?string
    {
        if (! $this->birth_date) {
            return null;
        }

        $d = (int) $this->birth_date->format('d');
        $m = (int) $this->birth_date->format('m');

        $zodiacs = [
            [1, 19, 'Capricorn'], [2, 18, 'Aquarius'], [3, 20, 'Pisces'],
            [4, 19, 'Aries'], [5, 20, 'Taurus'], [6, 20, 'Gemini'],
            [7, 22, 'Cancer'], [8, 22, 'Leo'], [9, 22, 'Virgo'],
            [10, 22, 'Libra'], [11, 21, 'Scorpio'], [12, 21, 'Sagittarius'],
            [12, 31, 'Capricorn'],
        ];

        foreach ($zodiacs as [$month, $lastDay, $name]) {
            if ($m === $month && $d <= $lastDay) {
                return $name;
            }
        }

        return null;
    }
}
