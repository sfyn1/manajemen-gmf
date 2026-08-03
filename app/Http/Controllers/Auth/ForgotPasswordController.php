<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // ── Step 1: Form input email ──────────────────────────────────────────────

    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);

        // Hapus OTP lama yang belum dipakai untuk email ini
        OtpCode::where('email', $request->email)
            ->where('is_used', false)
            ->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email'      => $request->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'is_used'    => false,
        ]);

        // Simpan email ke persistent session
        $request->session()->put('otp_email', $request->email);

        // Kirim via Queue
        Mail::to($request->email)->queue(new OtpMail($code));

        return redirect()->route('password.verify-otp')
            ->with('success', 'Kode OTP telah dikirim ke email Anda. Berlaku 10 menit.');
    }

    // ── Step 2: Verifikasi OTP ────────────────────────────────────────────────

    public function showVerifyForm(Request $request)
    {
        $email = $request->session()->get('otp_email');
        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }
        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $sessionEmail = $request->session()->get('otp_email');
        $email = $sessionEmail ?? $request->email;

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Ulangi proses dari awal.']);
        }

        $otp = OtpCode::where('email', $email)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->first();

        if (! $otp || ! $otp->isValid()) {
            return back()->withErrors(['code' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        $otp->update(['is_used' => true]);

        // Simpan status terverifikasi ke persistent session
        $request->session()->put('reset_email', $email);
        $request->session()->put('otp_verified', true);

        return redirect()->route('password.reset')
            ->with('success', 'OTP valid! Silakan buat password baru.');
    }

    // ── Step 3: Reset password ────────────────────────────────────────────────

    public function showResetForm(Request $request)
    {
        $email = $request->session()->get('reset_email');
        $verified = $request->session()->get('otp_verified');

        if (! $email || ! $verified) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Ulangi proses dari awal.']);
        }

        return view('auth.reset-password', compact('email'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $sessionEmail = $request->session()->get('reset_email');
        $verified     = $request->session()->get('otp_verified');
        $targetEmail  = $sessionEmail ?? $request->email;

        if (! $targetEmail || ! $verified) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Ulangi proses dari awal.']);
        }

        User::where('email', $targetEmail)
            ->update(['password' => Hash::make($request->password)]);

        // Bersihkan sesi
        $request->session()->forget(['otp_email', 'reset_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
