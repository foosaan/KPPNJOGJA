<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BerkasLayanan;
use App\Models\Divisi;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Admin
     * Logika:
     * 1. Menghitung total Admin, Staff, dan User untuk statistik.
     * 2. Mengambil semua data berkas dari database untuk ringkasan.
     * 3. Mengelompokkan berkas berdasarkan Divisi untuk ditampilkan dalam tab.
     */
    public function index()
    {
        // Total counts untuk summary cards (Menghitung jumlah user per role)
        $admins = User::where('role', 'admin')->latest()->get();
        $staffs = User::where('role', 'staff')->latest()->get();
        $users  = User::where('role', 'user')->latest()->get();

        // Ambil SEMUA berkas dari tabel berkas_layanans (Tabel Gabungan)
        $allBerkas = BerkasLayanan::with(['staff', 'divisi'])
            ->latest()
            ->get()
            ->map(function ($item) {
                // Menambahkan nama divisi ke setiap item agar mudah difilter
                $item->divisi_nama = $item->divisi->nama ?? 'Unknown';
                return $item;
            });

        // Ambil daftar divisi yang aktif untuk membuat Tab secara dinamis
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();

        // Mengelompokkan berkas berdasarkan divisi untuk isi Tab
        $berkasByDivisi = [];
        foreach ($divisis as $divisi) {
            $berkasByDivisi[$divisi->slug] = $allBerkas->filter(function($berkas) use ($divisi) {
                return strtolower($berkas->divisi_nama) === strtolower($divisi->nama);
            })->values();
        }

        // Mengirimkan semua data (variabel) ke tampilan (View: admin.dashboard)
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
