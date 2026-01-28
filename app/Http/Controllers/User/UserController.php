<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BerkasLayanan;
use App\Models\Divisi;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Menampilkan Dashboard User
     * - Menampilkan riwayat pengajuan layanan milik user tersebut
     */
    public function dashboard()
    {
        $nip = Auth::user()->nip;

        // Ambil daftar Divisi Aktif untuk Tab Navigasi
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();

        // 1. Ambil semua request milik user yang sedang login (Filter by NIP)
        $allRequests = BerkasLayanan::with(['divisi'])
            ->where('id_satker', $nip)
            ->latest()
            ->get();

        // 2. Tambahkan atribut ekstra untuk kebutuhan view
        $allRequests->each(function ($item) {
            $item->layanan_type = strtoupper($item->divisi->nama ?? 'LAINNYA');
            $item->divisi_nama = $item->divisi->nama ?? 'Lainnya';
        });

        // 3. Kelompokkan berdasarkan ID Divisi agar mudah ditampilkan per Tab
        $groupedByDivisi = $allRequests->groupBy('divisi_id');

        // 4. Ambil daftar tahun untuk opsi filter tahun
        $tahunList = $allRequests
            ->pluck('created_at')
            ->filter()
            ->map(fn($date) => optional($date)->format('Y'))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return view('user.dashboard', compact(
            'divisis',
            'allRequests',
            'groupedByDivisi',
            'tahunList'
        ));
    }
}