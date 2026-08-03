<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanQrController extends Controller
{
    public function index()
    {
        return view('admin.scan-qr.index');
    }

    public function scan(Request $request): JsonResponse
    {
        $request->validate(['token' => ['required', 'string']]);
        return $this->processToken($request->token);
    }

    public function verify(string $token): JsonResponse
    {
        return $this->processToken($token);
    }

    public function history()
    {
        $visits = Visit::with('member.package')->latest('visited_at')->paginate(20);
        return view('admin.scan-qr.history', compact('visits'));
    }

    private function processToken(string $token): JsonResponse
    {
        $token = trim($token);

        // Jika scanner membaca full URL (misal http://127.0.0.1:8000/qr/verify/TOKEN)
        if (str_contains($token, '/qr/verify/')) {
            $token = \Illuminate\Support\Str::afterLast($token, '/qr/verify/');
        }

        $member = Member::where('qr_token', $token)->with('package')->first();

        if (! $member) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak dikenali / tidak valid.'], 404);
        }

        if ($member->isExpired()) {
            return response()->json([
                'success'      => false,
                'member_name'  => $member->full_name,
                'package_name' => $member->package?->name,
                'member_photo' => $member->profile_photo_url,
                'message'      => "❌ Masa membership {$member->full_name} telah KADALUARSA sejak " . ($member->membership_end_date ? $member->membership_end_date->format('d M Y') : 'hari ini') . ". Akses presensi ditolak.",
            ], 403);
        }

        if (! $member->isActive()) {
            return response()->json([
                'success'      => false,
                'member_name'  => $member->full_name,
                'package_name' => $member->package?->name,
                'member_photo' => $member->profile_photo_url,
                'message'      => "Member {$member->full_name} tidak aktif (status: {$member->status}).",
            ], 403);
        }

        // Catat kunjungan (tanpa batasan frekuensi/jam)
        Visit::create([
            'member_id'  => $member->id,
            'visited_at' => now(),
        ]);

        return response()->json([
            'success'      => true,
            'member_name'  => $member->full_name,
            'package_name' => $member->package?->name,
            'expires_on'   => $member->membership_end_date?->format('d M Y'),
            'member_photo' => $member->profile_photo_url,
            'message'      => "✅ Presensi Berhasil! Selamat datang, {$member->full_name}.",
        ]);
    }
}
