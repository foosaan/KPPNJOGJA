<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vera;
use App\Models\LayananPd;
use App\Models\Mski;
use App\Models\Bank;
use App\Models\Umum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        $veraRequests = Vera::latest()->get();
        $pdRequests = LayananPd::latest()->get();
        $mskiRequests = Mski::latest()->get();
        $bankRequests = Bank::latest()->get();
        $umumRequests = Umum::latest()->get();

        $veraRequests->each(fn($r) => $r->layanan_type = 'VERA');
        $pdRequests->each(fn($r) => $r->layanan_type = 'PD');
        $mskiRequests->each(fn($r) => $r->layanan_type = 'MSKI');
        $bankRequests->each(fn($r) => $r->layanan_type = 'BANK');
        $umumRequests->each(fn($r) => $r->layanan_type = 'UMUM');


        $allRequests = $veraRequests->concat($pdRequests)
            ->concat($mskiRequests)
            ->concat($bankRequests)
            ->concat($umumRequests);

        $perPage = 15; //mengatur jumlah table yang di munculkan
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allRequests->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedRequests = new LengthAwarePaginator(
            $currentItems,
            $allRequests->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $veraCount = Vera::count();
        $pdCount = LayananPd::count();
        $mskiCount = Mski::count();
        $bankCount = Bank::count();
        $umumCount = Umum::count();

        $tahunList = $allRequests->pluck('created_at')
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('staff.dashboard', compact(
            'veraCount',
            'pdCount',
            'mskiCount',
            'bankCount',
            'umumCount',
            'paginatedRequests',
            'allRequests',
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests',
            'tahunList'
        ));
    }

    // ==================== BERKAS MASUK ====================
    public function index()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::latest()->get() : collect();
        $pdRequests = $divisi === 'PD' ? LayananPd::latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::latest()->get() : collect();
        $umumRequests = $divisi === 'UMUM' ? Umum::latest()->get() : collect();

        $allRequests = $veraRequests->concat($pdRequests)->concat($mskiRequests)->concat($bankRequests)->concat(($umumRequests));

        $tahunList = $allRequests->pluck('created_at')
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('staff.berkasmasuk', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests',
            'allRequests',
            'tahunList'
        ));
    }

    // ==================== UPDATE STATUS ====================
    public function updateStatus(Request $request, $id, $layanan_type)
    {
        $request->validate([
            'status' => 'required|string|in:baru,diproses,selesai,ditolak',
            'alasan_penolakan' => 'nullable|string|max:255',
        ]);

        // Tentukan model berdasarkan jenis layanan
        switch (strtolower($layanan_type)) {
            case 'vera':
                $requestData = Vera::findOrFail($id);
                break;
            case 'pd':
                $requestData = LayananPd::findOrFail($id);
                break;
            case 'mski':
                $requestData = Mski::findOrFail($id);
                break;
            case 'bank':
                $requestData = Bank::findOrFail($id);
                break;
            case 'umum':
                $requestData = Umum::findOrFail($id);
                break;

            default:
                return redirect()->back()->with('error', 'Jenis layanan tidak valid.');
        }

        $requestData->status = $request->status;

        if ($request->status === 'ditolak') {
            $requestData->alasan_penolakan = $request->alasan_penolakan;
        } else {
            $requestData->alasan_penolakan = null;
        }

        $requestData->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    // ==================== BERKAS DIPROSES ====================
    public function berkasProses()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'diproses')->latest()->get() : collect();
        $pdRequests = $divisi === 'PD' ? LayananPd::where('status', 'diproses')->latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'diproses')->latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'diproses')->latest()->get() : collect();
        $umumRequests = $divisi === 'UMUM' ? Umum::where('status', 'diproses')->latest()->get() : collect();

        return view('staff.berkasproses', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests'
        ));
    }

    // ==================== BERKAS SELESAI ====================
    public function berkasSelesai()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'selesai')->latest()->get() : collect();
        $pdRequests = $divisi === 'PD' ? LayananPd::where('status', 'selesai')->latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'selesai')->latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'selesai')->latest()->get() : collect();
        $umumRequests = $divisi === 'UMUM' ? Umum::where('status', 'selesai')->latest()->get() : collect();

        return view('staff.berkasselesai', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests'
        ));
    }

    // ==================== BERKAS DITOLAK ====================
    public function berkasDitolak()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'ditolak')->latest()->get() : collect();
        $pdRequests = $divisi === 'PD' ? LayananPd::where('status', 'ditolak')->latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'ditolak')->latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'ditolak')->latest()->get() : collect();
        $umumRequests = $divisi === 'UMUM' ? Umum::where('status', 'ditolak')->latest()->get() : collect();

        return view('staff.berkasditolak', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests'
        ));
    }

   public function updateFeedback(Request $request, $id)
{
    // Divisi staff yang login
    $divisi = strtoupper(Auth::user()->divisi);

    // Validasi (feedback wajib, file opsional)
    $validated = $request->validate([
        'feedback' => 'required|string|max:500',
        'feedback_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
    ]);

    // Tentukan model dan pesan sukses
    switch ($divisi) {
        case 'UMUM':
            $model = Umum::class;
            $successMessage = 'Feedback berhasil disimpan untuk layanan UMUM.';
            break;
        case 'BANK':
            $model = Bank::class;
            $successMessage = 'Feedback berhasil disimpan untuk layanan BANK.';
            break;
        case 'VERA':
            $model = Vera::class;
            $successMessage = 'Feedback berhasil disimpan untuk layanan VERA.';
            break;
        case 'PD':
            $model = LayananPd::class;
            $successMessage = 'Feedback berhasil disimpan untuk layanan PD.';
            break;
        case 'MSKI':
            $model = Mski::class;
            $successMessage = 'Feedback berhasil disimpan untuk layanan MSKI.';
            break;
        default:
            return redirect()->back()->with('error', 'Anda tidak diizinkan memberikan feedback untuk divisi ini.');
    }

    // Ambil data berdasarkan model dan id
    $data = $model::findOrFail($id);

    // Simpan file jika ada, dengan format nama khusus
    if ($request->hasFile('feedback_file')) {
        $file = $request->file('feedback_file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $today = now()->format('Ymd'); // format YYYYMMDD
        $newFileName = "{$divisi}-{$today}-{$originalName}.{$extension}";

        $filePath = $file->storeAs('feedback', $newFileName, 'public');
        $data->feedback_file = $filePath;
    }

    $data->feedback = $validated['feedback'];
    $data->save();

    return redirect()->back()->with('success', $successMessage);
}

}