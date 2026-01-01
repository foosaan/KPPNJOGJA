<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vera;
use App\Models\LayananPd;
use App\Models\Mski;
use App\Models\Bank;
use App\Models\Umum;
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

        // Ambil data dari masing-masing model hanya milik user yang sedang login
        $veraRequests = Vera::where('id_satker', $nip)->latest()->get();
        $pdRequests = LayananPd::where('id_satker', $nip)->latest()->get();
        $mskiRequests = Mski::where('id_satker', $nip)->latest()->get();
        $bankRequests = Bank::where('id_satker', $nip)->latest()->get();
        $umumRequests = Umum::where('id_satker', $nip)->latest()->get();

        // Ambil data dari berkas_layanans (divisi dinamis)
        $berkasGenerik = BerkasLayanan::with('divisi')
            ->where('id_satker', $nip)
            ->latest()
            ->get();

        // Gabungkan semua data untuk tab "Semua"
        $allRequests = new Collection();

        $veraRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'VERA';
            $item->divisi_nama = 'VERA';
            $allRequests->push($item);
        });

        $pdRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'PD';
            $item->divisi_nama = 'PD';
            $allRequests->push($item);
        });

        $mskiRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'MSKI';
            $item->divisi_nama = 'MSKI';
            $allRequests->push($item);
        });

        $bankRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'BANK';
            $item->divisi_nama = 'BANK';
            $allRequests->push($item);
        });

        $umumRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'UMUM';
            $item->divisi_nama = 'UMUM';
            $allRequests->push($item);
        });

        $berkasGenerik->each(function ($item) use ($allRequests) {
            $item->layanan_type = strtoupper($item->divisi->nama ?? 'GENERIK');
            $item->divisi_nama = $item->divisi->nama ?? 'Lainnya';
            $allRequests->push($item);
        });

        // Urutkan berdasarkan created_at terbaru
        $allRequests = $allRequests->sortByDesc('created_at')->values();

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
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests',
            'berkasGenerik',
            'allRequests',
            'tahunList'
        ));
    }
}