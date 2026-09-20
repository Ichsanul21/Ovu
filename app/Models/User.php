<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'share_sensitive_with_partner', 'partner_code'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'share_sensitive_with_partner' => 'boolean',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class)->orderBy('start_date');
    }

    public function dailyLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class)->orderBy('log_date');
    }

    public function prediction(): HasOne
    {
        return $this->hasOne(PredictionCache::class);
    }

    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function isWife(): bool
    {
        return $this->role === 'wife';
    }

    /** Akun yang datanya boleh dilihat user ini (sendiri atau pasangan yang mengundang). */
    public function visibleUserId(): int
    {
        if ($this->isWife() || ! $this->partner_code) {
            return $this->id;
        }

        $owner = self::where('role', 'wife')
            ->whereHas('partnerInvites', fn ($q) => $q->where('code', $this->partner_code))
            ->first();

        return $owner ? $owner->id : $this->id;
    }

    public function partnerInvites(): HasMany
    {
        return $this->hasMany(PartnerInvite::class);
    }
}
