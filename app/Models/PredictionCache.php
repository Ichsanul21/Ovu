<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PredictionCache extends Model
{
    protected $table = 'predictions_cache';

    protected $fillable = [
        'user_id', 'next_period', 'ovulation_date', 'fertile_start',
        'fertile_end', 'confidence', 'avg_cycle', 'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'next_period' => 'date',
            'ovulation_date' => 'date',
            'fertile_start' => 'date',
            'fertile_end' => 'date',
            'generated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
