<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoachManagementController extends Controller
{
    public function index()
    {
        $coaches = Coach::with(['user', 'schedules'])->paginate(10);
        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.form', ['coach' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'bio'              => ['nullable', 'string'],
            'rate_per_session' => ['required', 'numeric', 'min:0'],
            'is_active'        => ['boolean'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'coach',
        ]);

        Coach::create([
            'user_id'          => $user->id,
            'full_name'        => $user->name,
            'phone'            => $data['phone'] ?? null,
            'bio'              => $data['bio'] ?? null,
            'rate_per_session' => $data['rate_per_session'],
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coaches.index')->with('success', "Coach {$user->name} berhasil ditambahkan.");
    }

    public function edit(Coach $coach)
    {
        $coach->load('user');
        return view('admin.coaches.form', compact('coach'));
    }

    public function update(Coach $coach, Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', "unique:users,email,{$coach->user_id}"],
            'password'         => ['nullable', 'string', 'min:8'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'bio'              => ['nullable', 'string'],
            'rate_per_session' => ['required', 'numeric', 'min:0'],
            'is_active'        => ['boolean'],
        ]);

        $userData = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $coach->user->update($userData);

        $coach->update([
            'full_name'        => $data['name'],
            'phone'            => $data['phone'] ?? null,
            'bio'              => $data['bio'] ?? null,
            'rate_per_session' => $data['rate_per_session'],
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coaches.index')->with('success', "Data coach berhasil diperbarui.");
    }

    public function destroy(Coach $coach)
    {
        if ($coach->schedules()->where('is_active', true)->exists()) {
            return redirect()->back()->with('error', 'Coach masih memiliki jadwal kelas aktif. Nonaktifkan jadwal terlebih dahulu.');
        }

        $name = $coach->user->name;
        $coach->user->delete();
        $coach->delete();

        return redirect()->route('admin.coaches.index')->with('success', "Coach {$name} berhasil dihapus.");
    }
}
