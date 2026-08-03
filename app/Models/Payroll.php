<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'year',
        'month',
        'total_sessions',
        'rate_per_session',
        'total_amount',
        'status',
        'paid_at',
        'paid_by',
        'notes',
    ];

    protected $casts = [
        'rate_per_session' => 'decimal:2',
        'total_amount'     => 'decimal:2',
        'paid_at'          => 'datetime',
    ];

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function getMonthLabelAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',       6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',   9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return ($months[$this->month] ?? $this->month) . ' ' . $this->year;
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function paidByUser()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
