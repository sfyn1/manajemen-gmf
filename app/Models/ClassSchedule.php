<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_type_id',
        'coach_id',
        'day_of_week',
        'start_time',
        'end_time',
        'max_capacity',
        'session_fee',
        'is_active',
    ];

    protected $casts = [
        'session_fee'  => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    // ── Days map ─────────────────────────────────────────────────────────────

    const DAYS = [
        'monday'    => 'Senin',
        'tuesday'   => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday'  => 'Kamis',
        'friday'    => 'Jumat',
        'saturday'  => 'Sabtu',
        'sunday'    => 'Minggu',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, string $day)
    {
        return $query->where('day_of_week', $day);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function getDayLabelAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? $this->day_of_week;
    }

    /**
     * Hitung tanggal sesi kelas berikutnya. Jika hari ini adalah hari kelas dan jam kelas sudah lewat,
     * otomatis lanjut ke minggu depan.
     */
    public function calculateNextDate(\Carbon\Carbon $today = null): string
    {
        $today = $today ? $today->copy() : \Carbon\Carbon::now();
        $dayMap = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];
        $targetDay = $dayMap[strtolower($this->day_of_week)] ?? 1;
        $daysUntil = ($targetDay - $today->dayOfWeek + 7) % 7;

        if ($daysUntil === 0) {
            $endTimeStr = $this->end_time ?? $this->start_time;
            $classEndTime = \Carbon\Carbon::parse($today->format('Y-m-d') . ' ' . $endTimeStr);
            if ($today->gt($classEndTime)) {
                $daysUntil = 7;
            }
        }

        return $today->addDays($daysUntil)->format('Y-m-d');
    }

    public function getAvailableSlots(string $date): int
    {
        $booked = $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['booked', 'attended'])
            ->count();
        return max(0, $this->max_capacity - $booked);
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_schedule_id');
    }

    public function attendanceVerifications()
    {
        return $this->hasMany(AttendanceVerification::class);
    }
}
