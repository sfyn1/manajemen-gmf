<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use App\Models\StaffInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StaffRegistrationController extends Controller
{
    /**
     * Tampilkan halaman registrasi staff via token undangan
     */
    public function showForm(Request $request)
    {
        // Jika sedang login (misal sebagai Owner di browser yang sama), logout dulu agar bisa mendaftar akun staff baru
        if (auth()->check()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $token = $request->query('token');

        if (! $token) {
            return view('auth.register-staff-invalid', [
                'message' => 'Token undangan tidak ditemukan. Pastikan Anda membuka link lengkap yang dikirimkan via email.'
            ]);
        }

        $invitation = StaffInvitation::where('token', $token)->first();

        if (! $invitation) {
            return view('auth.register-staff-invalid', [
                'message' => 'Link undangan registrasi tidak valid atau tidak ditemukan di sistem.'
            ]);
        }

        if ($invitation->status === 'used') {
            return view('auth.register-staff-invalid', [
                'message' => 'Link undangan ini sudah pernah digunakan untuk mendaftar akun.'
            ]);
        }

        if ($invitation->status === 'expired' || Carbon::now()->gt($invitation->expired_at)) {
            $invitation->update(['status' => 'expired']);
            return view('auth.register-staff-invalid', [
                'message' => 'Link undangan registrasi ini telah kedaluwarsa (masa berlaku 48 jam).'
            ]);
        }

        return view('auth.register-staff', compact('invitation'));
    }

    /**
     * Eksekusi registrasi akun staff baru via token undangan
     */
    public function submitForm(Request $request)
    {
        $request->validate([
            'token'                 => ['required', 'string', 'exists:staff_invitations,token'],
            'name'                  => ['required', 'string', 'max:100'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'phone'                 => ['nullable', 'string', 'max:20'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'password.required'     => 'Kata sandi wajib diisi.',
            'password.min'          => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $invitation = StaffInvitation::where('token', $request->token)->firstOrFail();

        if (! $invitation->isValid()) {
            return redirect()->route('login')->with('error', 'Link undangan registrasi sudah tidak berlaku.');
        }

        // Cek jika email sudah terdaftar di users (pencegahan ganda)
        if (User::where('email', $invitation->email)->exists()) {
            $invitation->markAsUsed();
            return redirect()->route('login')->with('error', 'Email ini sudah memiliki akun di sistem.');
        }

        // Buat akun User (langsung aktif untuk staff yang diundang Owner)
        $user = User::create([
            'name'      => $request->name,
            'email'     => $invitation->email,
            'password'  => Hash::make($request->password),
            'role'      => $invitation->role,
            'phone'     => $request->phone,
            'is_active' => true,
        ]);

        // Jika role == coach, buat profil Coach
        if ($invitation->role === 'coach') {
            Coach::create([
                'user_id'          => $user->id,
                'full_name'        => $user->name,
                'phone'            => $request->phone,
                'rate_per_session' => 0,
                'is_active'        => true,
            ]);
        }

        // Mark invitation token as used
        $invitation->markAsUsed();

        // Login otomatis
        auth()->login($user);

        $targetRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'coach' => 'coach.dashboard',
            default => 'home',
        };

        $roleLabel = ucfirst($user->role);
        return redirect()->route($targetRoute)->with('success', "Selamat datang, {$user->name}! Akun staff ({$roleLabel}) Anda berhasil diaktifkan.");
    }
}
