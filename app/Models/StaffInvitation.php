<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StaffInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'role',
        'token',
        'status',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // ── Helper Methods ────────────────────────────────────────────────────────

    /**
     * Cek apakah token undangan masih berlaku dan belum digunakan
     */
    public function isValid(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        if ($this->expired_at && Carbon::now()->gt($this->expired_at)) {
            $this->update(['status' => 'expired']);
            return false;
        }

        return true;
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(): void
    {
        $this->update(['status' => 'used']);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending')->where('expired_at', '>', Carbon::now());
    }
}
