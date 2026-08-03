<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_package_id',
        'full_name',
        'nik',
        'gender',
        'birth_date',
        'address',
        'phone',
        'email',
        'profile_photo_path',
        'status',
        'rejection_reason',
        'membership_start_date',
        'membership_end_date',
        'qr_token',
        'qr_expires_at',
    ];

    protected $casts = [
        'birth_date'            => 'date',
        'membership_start_date' => 'date',
        'membership_end_date'   => 'date',
        'qr_expires_at'         => 'datetime',
    ];

    // ── Status Constants ──────────────────────────────────────────────────────

    const STATUS_PENDING    = 'pending_verification';
    const STATUS_ACTIVE     = 'active';
    const STATUS_REJECTED   = 'rejected';
    const STATUS_EXPIRED    = 'expired';

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('membership_end_date')
                    ->orWhere('membership_end_date', '>=', now()->startOfDay());
            });
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_EXPIRED)
                ->orWhere(function ($q2) {
                    $q2->where('status', self::STATUS_ACTIVE)
                        ->whereNotNull('membership_end_date')
                        ->where('membership_end_date', '<', now()->startOfDay());
                });
        });
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        if (in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_EXPIRED])) {
            if ($this->membership_end_date && $this->membership_end_date->gte(now()->startOfDay())) {
                if ($this->status !== self::STATUS_ACTIVE) {
                    $this->update(['status' => self::STATUS_ACTIVE]);
                }
                return true;
            }

            if ($this->membership_end_date && $this->membership_end_date->lt(now()->startOfDay())) {
                if ($this->status !== self::STATUS_EXPIRED) {
                    $this->update(['status' => self::STATUS_EXPIRED]);
                }
                return false;
            }
        }

        return $this->status === self::STATUS_ACTIVE;
    }

    public function isExpired(): bool
    {
        if (in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_EXPIRED])) {
            if ($this->membership_end_date && $this->membership_end_date->lt(now()->startOfDay())) {
                if ($this->status !== self::STATUS_EXPIRED) {
                    $this->update(['status' => self::STATUS_EXPIRED]);
                }
                return true;
            }

            if ($this->membership_end_date && $this->membership_end_date->gte(now()->startOfDay())) {
                if ($this->status !== self::STATUS_ACTIVE) {
                    $this->update(['status' => self::STATUS_ACTIVE]);
                }
                return false;
            }
        }

        return false;
    }

    public function isQrValid(): bool
    {
        if (! $this->isActive() || ! $this->qr_token) {
            return false;
        }

        if ($this->qr_expires_at) {
            return now()->lt($this->qr_expires_at);
        }

        return now()->lte($this->membership_end_date);
    }

    public function isDailyPass(): bool
    {
        return $this->package?->type === 'daily';
    }

    public function isMonthlyMember(): bool
    {
        $type = $this->package?->type;
        return in_array($type, ['monthly_regular', 'monthly_student']);
    }

    public function generateQrToken(): string
    {
        $token = Str::random(48);
        $this->update(['qr_token' => $token]);
        return $token;
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(MembershipPackage::class, 'membership_package_id');
    }

    public function documents()
    {
        return $this->hasMany(MembershipDocument::class);
    }

    public function latestDocument()
    {
        return $this->hasOne(MembershipDocument::class)->latestOfMany();
    }

    public function renewals()
    {
        return $this->hasMany(MembershipRenewal::class);
    }

    public function latestPendingRenewal()
    {
        return $this->hasOne(MembershipRenewal::class)->where('status', 'pending')->latestOfMany();
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo_path)) {
            return asset('storage/' . $this->profile_photo_path);
        }

        $name = urlencode($this->full_name ?? 'Member');
        return "https://ui-avatars.com/api/?name={$name}&color=FFFFFF&background=f05a2a&bold=true";
    }
}
