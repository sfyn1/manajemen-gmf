<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'identity_document_path',
        'identity_document_type',
        'payment_proof_path',
        'payment_amount',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
