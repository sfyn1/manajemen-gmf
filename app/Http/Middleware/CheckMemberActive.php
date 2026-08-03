<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberActive
{
    /**
     * Middleware kontrol akses member:
     * - Pending / Rejected: logout & tidak dapat mengakses portal member.
     * - Expired: DIIZINKAN login & mengakses Dashboard, Profil, serta Pengajuan Perpanjangan.
     *   TAPI fitur lain (Booking Kelas, Riwayat Kelas, Invoice) DIKUNCI & diredirect ke Dashboard dengan peringatan.
     * - Active: DIIZINKAN mengakses semua fitur.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'member') {
            $member = $user->member;

            // Jika member tidak ada atau status pendaftaran belum disetujui admin / ditolak
            if (! $member || in_array($member->status, [\App\Models\Member::STATUS_PENDING, \App\Models\Member::STATUS_REJECTED])) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Akun Anda belum disetujui atau pendaftaran Anda ditolak oleh Admin. Silakan hubungi administrator.');
            }

            // Pemicu evaluasi otomatis tanggal kadaluarsa (jika tanggal berubah/back-date)
            $isExpired = $member->isExpired();

            // Jika member kadaluarsa
            if ($isExpired) {
                $routeName = $request->route()?->getName();
                $allowedRoutes = [
                    'member.dashboard',
                    'member.profile.index',
                    'member.renewal.store',
                ];

                if (! in_array($routeName, $allowedRoutes)) {
                    return redirect()->route('member.dashboard')
                        ->with('error', '⚠️ Masa membership Anda telah kadaluarsa! Silakan perpanjang membership terlebih dahulu untuk mengakses fitur ini.');
                }
            }
        }

        return $next($request);
    }
}
