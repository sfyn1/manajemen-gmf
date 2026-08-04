<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use App\Models\StaffInvitation;
use App\Mail\StaffInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StaffAccountController extends Controller
{
    /**
     * Tampilkan halaman kelola akun staff & tabel monitoring undangan
     */
    public function index()
    {
        $staff = User::whereIn('role', ['admin', 'coach'])->with('coach')->latest()->get();
        $invitations = StaffInvitation::latest()->get();

        // Update status expired otomatis untuk tampilan
        foreach ($invitations as $inv) {
            if ($inv->status === 'pending' && Carbon::now()->gt($inv->expired_at)) {
                $inv->update(['status' => 'expired']);
            }
        }

        return view('owner.staff.index', compact('staff', 'invitations'));
    }

    /**
     * Owner mengirimkan undangan registrasi staff (Admin / Coach) via Email
     */
    public function invite(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'role'  => ['required', 'in:admin,coach'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai pengguna aktif di sistem.',
            'role.required' => 'Pilih peran staff (Admin atau Coach).',
        ]);

        // Cek jika sudah ada undangan pending untuk email yang sama
        $existingPending = StaffInvitation::where('email', $request->email)
            ->where('status', 'pending')
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if ($existingPending) {
            return redirect()->back()->with('error', "Undangan registrasi untuk email '{$request->email}' sudah pernah dikirim dan masih aktif hingga {$existingPending->expired_at->format('d M Y H:i')}.");
        }

        $token = Str::random(40);

        $invitation = StaffInvitation::create([
            'email'      => $request->email,
            'role'       => $request->role,
            'token'      => $token,
            'status'     => 'pending',
            'expired_at' => Carbon::now()->addHours(48),
        ]);

        try {
            Mail::to($invitation->email)->send(new StaffInvitationMail($invitation));
            $roleLabel = ucfirst($invitation->role);
            return redirect()->route('owner.staff.index')->with('success', "Undangan registrasi staff ({$roleLabel}) berhasil dikirim ke email {$invitation->email}.");
        } catch (\Exception $e) {
            return redirect()->route('owner.staff.index')->with('success', "Token undangan staff ({$invitation->role}) berhasil dibuat. (Catatan: Pengiriman email dinonaktifkan di local environment. Link registrasi: " . route('register.staff', ['token' => $token]) . ")");
        }
    }

    /**
     * Membatalkan / menghapus token undangan
     */
    public function cancelInvitation(StaffInvitation $invitation)
    {
        $invitation->delete();
        return redirect()->back()->with('success', 'Undangan registrasi staff berhasil dibatalkan.');
    }

    public function edit(User $user)
    {
        return view('owner.staff.form', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', "unique:users,email,{$user->id}"],
            'password'  => ['nullable', 'string', 'min:8'],
            'role'      => ['required', 'in:admin,coach'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'phone'     => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
            ...(filled($data['password']) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        if ($data['role'] === 'coach' && $user->coach) {
            $user->coach->update(['phone' => $data['phone'] ?? null]);
        }

        return redirect()->route('owner.staff.index')->with('success', 'Akun staff berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'owner' || $user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun ini.');
        }

        try {
            if ($user->coach) {
                $hasData = $user->coach->schedules()->exists()
                    || $user->coach->attendanceVerifications()->exists()
                    || $user->coach->payrolls()->exists();

                if ($hasData) {
                    return redirect()->back()->with('error', "Tidak dapat menghapus akun '{$user->name}' karena memiliki riwayat jadwal kelas, presensi, atau payroll. Silakan matikan sakelar status 'Aktif' untuk menonaktifkan akun staff ini.");
                }
            }

            $user->delete();
            return redirect()->route('owner.staff.index')->with('success', "Akun '{$user->name}' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Gagal menghapus akun: data staff ini masih terikat dengan riwayat transaksi/sistem.");
        }
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if (request()->wantsJson()) {
            return response()->json(['is_active' => $user->is_active]);
        }

        return redirect()->back()->with('success', "Status akun '{$user->name}' berhasil {$statusText}.");
    }
}
