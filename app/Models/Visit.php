<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'visited_at',
        'scanned_by_ip',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
