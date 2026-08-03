<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'bio',
        'photo_path',
        'rate_per_session',
        'is_active',
    ];

    protected $casts = [
        'rate_per_session' => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function attendanceVerifications()
    {
        return $this->hasMany(AttendanceVerification::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
