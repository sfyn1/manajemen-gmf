<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'duration_days',
        'description',
        'required_document',
        'is_active',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'is_active'   => 'boolean',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isDaily(): bool
    {
        return $this->type === 'daily';
    }

    public function isStudent(): bool
    {
        return $this->type === 'monthly_student';
    }

    public function requiresKtm(): bool
    {
        return $this->required_document === 'ktm';
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
