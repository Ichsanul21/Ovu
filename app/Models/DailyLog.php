<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyLog extends Model
{
    protected $fillable = [
        'user_id', 'log_date', 'bleeding', 'cramp', 'headache', 'breast_pain',
        'acne', 'nausea', 'mood', 'energy', 'sleep_hours', 'stress',
        'cervical_fluid', 'bbt', 'lh_test', 'testpack', 'intercourse',
        'protected', 'weight_kg', 'symptoms', 'diary',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'intercourse' => 'boolean',
            'protected' => 'boolean',
            'bbt' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'sleep_hours' => 'decimal:1',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function symptomsList(): array
    {
        if (! $this->symptoms) {
            return [];
        }

        $decoded = json_decode($this->symptoms, true);

        return is_array($decoded) ? $decoded : [];
    }
}
