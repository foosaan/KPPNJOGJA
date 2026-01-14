<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BerkasLayanan;
use App\Models\Divisi;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function dashboard()
    {
        $nip = Auth::user()->nip;

        // Get all active divisi for tabs
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();

        // Ambil semua data berkas milik user yang sedang login
        $allRequests = BerkasLayanan::with(['divisi'])
            ->where('id_satker', $nip)
            ->latest()
            ->get();

        // Add layanan_type and divisi_nama for each item
        $allRequests->each(function ($item) {
            $item->layanan_type = strtoupper($item->divisi->nama ?? 'LAINNYA');
            $item->divisi_nama = $item->divisi->nama ?? 'Lainnya';
        });

        // Group requests by divisi for tabs
        $groupedByDivisi = $allRequests->groupBy('divisi_id');

        // Ambil daftar tahun unik dari semua request
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