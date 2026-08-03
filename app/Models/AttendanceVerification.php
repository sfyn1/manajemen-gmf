<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'class_schedule_id',
        'session_date',
        'status',
        'photo_path',
        'submitted_at',
        'deadline_at',
        'reviewed_at',
        'reviewed_by',
        'rejection_reason',
        'commission_paid',
        'commission_amount',
    ];

    protected $casts = [
        'session_date'    => 'date',
        'submitted_at'    => 'datetime',
        'deadline_at'     => 'datetime',
        'reviewed_at'     => 'datetime',
        'commission_paid' => 'boolean',
        'commission_amount' => 'decimal:2',
    ];

    /**
     * Otomatis sinkronisasi & buat tugas presensi AttendanceVerification untuk sesi kelas yang sudah lewat / hari ini
     */
    public static function syncFromBookings()
    {
        $todayStr = now()->toDateString();

        // 1. Sync berdasarkan ClassBooking yang ada di database
        $pastBookings = ClassBooking::where('booking_date', '<=', $todayStr)
            ->whereIn('status', [ClassBooking::STATUS_BOOKED, ClassBooking::STATUS_ATTENDED])
            ->with('schedule')
            ->get();

        foreach ($pastBookings as $booking) {
            $schedule = $booking->schedule;
            if (!$schedule || !$schedule->coach_id) continue;

            $dateStr = $booking->booking_date->format('Y-m-d');

            $commissionAmount = $schedule->coach?->rate_per_session ?: $schedule->session_fee;

            static::firstOrCreate([
                'coach_id'          => $schedule->coach_id,
                'class_schedule_id' => $schedule->id,
                'session_date'      => $dateStr,
            ], [
                'status'            => self::STATUS_PENDING,
                'submitted_at'      => null,
                'deadline_at'       => Carbon::parse($dateStr)->addDays(3),
                'commission_amount' => $commissionAmount,
            ]);
        }

        // 2. Sync berdasarkan ClassSchedule aktif yang hari pengajarannya sudah pernah lewat dalam 14 hari terakhir
        $activeSchedules = ClassSchedule::active()->with('coach')->get();
        for ($i = 0; $i <= 14; $i++) {
            $checkDate = now()->subDays($i);
            $checkDayName = strtolower($checkDate->format('l'));
            $checkDateStr = $checkDate->format('Y-m-d');

            foreach ($activeSchedules as $schedule) {
                if (strtolower($schedule->day_of_week) === $checkDayName && $schedule->coach_id) {
                    $hasBookings = ClassBooking::where('class_schedule_id', $schedule->id)
                        ->where('booking_date', $checkDateStr)
                        ->whereIn('status', [ClassBooking::STATUS_BOOKED, ClassBooking::STATUS_ATTENDED])
                        ->exists();

                    if ($hasBookings) {
                        $commissionAmount = $schedule->coach?->rate_per_session ?: $schedule->session_fee;

                        static::firstOrCreate([
                            'coach_id'          => $schedule->coach_id,
                            'class_schedule_id' => $schedule->id,
                            'session_date'      => $checkDateStr,
                        ], [
                            'status'            => self::STATUS_PENDING,
                            'submitted_at'      => null,
                            'deadline_at'       => $checkDate->copy()->addDays(3),
                            'commission_amount' => $commissionAmount,
                        ]);
                    }
                }
            }
        }
    }

    // ── Status Constants ──────────────────────────────────────────────────────

    const STATUS_PENDING     = 'pending';
    const STATUS_APPROVED    = 'approved';
    const STATUS_REJECTED    = 'rejected';
    const STATUS_AUTO_FAILED = 'auto_failed';

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isPending(): bool    { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool   { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool   { return $this->status === self::STATUS_REJECTED; }
    public function isAutoFailed(): bool { return $this->status === self::STATUS_AUTO_FAILED; }

    /**
     * Cek apakah coach masih dalam window waktu untuk resubmit
     */
    public function canResubmit(): bool
    {
        return $this->status === self::STATUS_REJECTED
            && $this->deadline_at
            && now()->lt($this->deadline_at);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
