<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil user berdasarkan role (pastikan value role sesuai dengan yang di DB)
        $admins = User::where('role', 'admin')->latest()->get();
        $staffs = User::where('role', 'staff')->latest()->get();
        $users  = User::where('role', 'user')->latest()->get();

        // Gabungkan jadi satu collection untuk tab "Semua"
        $allAccounts = new Collection();

        $admins->each(function ($item) use ($allAccounts) {
            $item->account_type = 'Admin';
            $allAccounts->push($item);
        });

        $staffs->each(function ($item) use ($allAccounts) {
            $item->account_type = 'Staff';
            $allAccounts->push($item);
        });

        $users->each(function ($item) use ($allAccounts) {
            $item->account_type = 'User';
            $allAccounts->push($item);
        });

        // Urutkan berdasarkan created_at terbaru
        $allAccounts = $allAccounts->sortByDesc('created_at')->values();

        // Total counts (jika view memakai $totalAdmins / $totalStaffs / $totalUsers)
        $totalAdmins = $admins->count();
        $totalStaffs = $staffs->count();
        $totalUsers  = $users->count();

        // Kirim semua variabel ke view
        return view('admin.dashboard', compact(
            'admins',
            'staffs',
            'users',
            'allAccounts',
            'totalAdmins',
            'totalStaffs',
            'totalUsers'
        ));
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $this->_logout($request);

        return redirect('/')->with('success', 'Anda telah logout.');
    }

    public function _logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
