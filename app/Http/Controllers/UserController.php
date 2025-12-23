<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vera;
use App\Models\LayananPd;
use App\Models\Mski;
use App\Models\Bank;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function dashboard()
    {
        $nip = Auth::user()->nip;

        // Ambil data dari masing-masing model hanya milik user yang sedang login
        $veraRequests = Vera::where('id_satker', $nip)->latest()->get();
        $pdRequests   = LayananPd::where('id_satker', $nip)->latest()->get();
        $mskiRequests = Mski::where('id_satker', $nip)->latest()->get();
        $bankRequests = Bank::where('id_satker', $nip)->latest()->get();

        // Gabungkan semua data untuk tab "Semua"
        $allRequests = new Collection();

        $veraRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'VERA';
            $allRequests->push($item);
        });

        $pdRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'PD';
            $allRequests->push($item);
        });

        $mskiRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'MSKI';
            $allRequests->push($item);
        });

        $bankRequests->each(function ($item) use ($allRequests) {
            $item->layanan_type = 'BANK';
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
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'allRequests',
            'tahunList'
        ));
    }
}
