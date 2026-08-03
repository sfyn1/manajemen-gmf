<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_photo_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isCoach(): bool    { return $this->role === 'coach'; }
    public function isMember(): bool   { return $this->role === 'member'; }
    public function isOwner(): bool    { return $this->role === 'owner'; }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo_path)) {
            return asset('storage/' . $this->profile_photo_path);
        }

        if ($this->member && $this->member->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->member->profile_photo_path)) {
            return asset('storage/' . $this->member->profile_photo_path);
        }

        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&color=FFFFFF&background=f05a2a&bold=true";
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function coach()
    {
        return $this->hasOne(Coach::class);
    }
}
