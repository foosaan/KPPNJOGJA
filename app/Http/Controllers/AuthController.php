<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function login(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    // Proses login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role == 'staff') {
                return redirect()->route('staff.dashboard');
            } elseif ($role == 'user') {
                return redirect()->route('user.dashboard');
            } else {
                Auth::logout();
                return redirect('/')->with('error', 'Role tidak dikenal.');
            }
        }

        return back()->with('error', 'Email atau password salah.');
    }

    // Tampilkan form register
    public function registerView(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    // Proses registrasi user biasa
    public function register(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'nip' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $request->input('name'),
            'nip' => $request->input('nip'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'user',
        ]);

        return redirect('/')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/')->with('success', 'Anda telah logout.');
    }

    // Fungsi bantu redirect berdasarkan role
    protected function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard')->with('success', 'Login sebagai Admin');
            case 'staff':
                return redirect('/staff/dashboard')->with('success', 'Login sebagai Staff');
            case 'user':
                return redirect('/user/dashboard')->with('success', 'Login sebagai User');
            default:
                return redirect('/')->with('error', 'Role tidak dikenali.');
        }
    }
}
