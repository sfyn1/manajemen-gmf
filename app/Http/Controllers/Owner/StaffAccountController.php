<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAccountController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['admin', 'coach'])->with('coach')->latest()->get();
        return view('owner.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('owner.staff.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', 'in:admin,coach'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'phone'     => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($data['role'] === 'coach') {
            Coach::create([
                'user_id'          => $user->id,
                'phone'            => $data['phone'] ?? null,
                'rate_per_session' => 0,
                'is_active'        => true,
            ]);
        }

        return redirect()->route('owner.staff.index')->with('success', "Akun {$user->name} berhasil dibuat.");
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
