<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Blok user yang dinonaktifkan
            if (! $user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('email');
            }

            // Redirect berdasarkan role
            return redirect()->intended($this->redirectTo($user->role));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    private function redirectTo(string $role): string
    {
        return match ($role) {
            'owner'  => route('owner.dashboard'),
            'admin'  => route('admin.dashboard'),
            'coach'  => route('coach.dashboard'),
            'member' => route('member.dashboard'),
            default  => '/',
        };
    }
}
