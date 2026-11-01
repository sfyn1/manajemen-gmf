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
        'order_id',
        'snap_token',
        'payment_type',
        'payment_status',
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
     * SOP pembatalan: hanya boleh sampai sebelum pukul 00:00 pada tanggal kelas.
     * Begitu masuk hari-H (WIB), pembatalan ditutup, termasuk setelah kelas selesai.
     */
    public function cancellationCutoff(): \Carbon\Carbon
    {
        return $this->booking_date->copy()->startOfDay();
    }

    public function isCancellable(): bool
    {
        return $this->status === self::STATUS_BOOKED
            && now()->lt($this->cancellationCutoff());
    }

    /**
     * Waktu selesai sesi berdasarkan tanggal booking dan jadwal kelas.
     */
    public function sessionEndAt(): ?\Carbon\Carbon
    {
        if (! $this->booking_date || ! $this->relationLoaded('schedule') && ! $this->schedule) {
            return null;
        }

        $endTime = $this->schedule?->end_time ?: $this->schedule?->start_time;
        return $endTime
            ? \Carbon\Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $endTime)
            : null;
    }

    /**
     * Booking yang belum dibatalkan otomatis selesai setelah jam kelas berakhir.
     * Hanya status booked yang boleh diubah agar status cancelled tidak berubah.
     */
    public static function markCompletedSessions(): int
    {
        $updated = 0;
        static::where('status', self::STATUS_BOOKED)
            ->with('schedule')
            ->where('booking_date', '<=', now()->toDateString())
            ->get()
            ->each(function (self $booking) use (&$updated) {
                $end = $booking->sessionEndAt();
                if ($end && now()->gte($end)) {
                    $booking->update(['status' => self::STATUS_ATTENDED]);
                    $updated++;
                }
            });

        return $updated;
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
