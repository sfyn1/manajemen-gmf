<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'class_schedule_id',
        'booking_date',
        'status',
        'payment_confirmed',
        'payment_amount',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date'      => 'date',
        'payment_confirmed' => 'boolean',
        'payment_amount'    => 'decimal:2',
        'cancelled_at'      => 'datetime',
    ];

    // ── Status Constants ──────────────────────────────────────────────────────

    const STATUS_BOOKED    = 'booked';
    const STATUS_ATTENDED  = 'attended';
    const STATUS_NO_SHOW   = 'no_show';
    const STATUS_CANCELLED = 'cancelled';

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Cek apakah booking masih bisa dibatalkan (sebelum tanggal kelas berlalu)
     */
    public function isCancellable(): bool
    {
        return $this->status === self::STATUS_BOOKED
            && $this->booking_date->gte(now()->startOfDay());
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_BOOKED, self::STATUS_ATTENDED]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now()->toDateString())
                     ->where('status', self::STATUS_BOOKED);
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }
}
