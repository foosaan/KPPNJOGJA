<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BerkasLayanan;
use App\Models\Divisi;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function index()
    {
        // Total counts untuk summary cards
        $admins = User::where('role', 'admin')->latest()->get();
        $staffs = User::where('role', 'staff')->latest()->get();
        $users  = User::where('role', 'user')->latest()->get();

        // Ambil berkas dari tabel berkas_layanans (unified)
        $allBerkas = BerkasLayanan::with(['staff', 'divisi'])
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->divisi_nama = $item->divisi->nama ?? 'Unknown';
                return $item;
            });

        // Get all active divisi for dynamic tabs
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();

        // Group berkas by divisi for dynamic tabs
        $berkasByDivisi = [];
        foreach ($divisis as $divisi) {
            $berkasByDivisi[$divisi->slug] = $allBerkas->filter(function($berkas) use ($divisi) {
                return strtolower($berkas->divisi_nama) === strtolower($divisi->nama);
            })->values();
        }

        // Kirim semua variabel ke view
        return view('admin.dashboard', compact(
            'admins',
            'staffs',
            'users',
            'allBerkas',
            'divisis',
            'berkasByDivisi'
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
