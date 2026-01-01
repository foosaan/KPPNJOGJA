<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vera;
use App\Models\LayananPd;
use App\Models\Mski;
use App\Models\Bank;
use App\Models\Umum;
use App\Models\BerkasLayanan;
use App\Models\Divisi;
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

        // Include berkas from berkas_layanans table
        $berkasGenerik = BerkasLayanan::with('divisi')->latest()->get();
        $berkasGenerik->each(function($r) {
            $r->layanan_type = strtoupper($r->divisi->nama ?? 'UNKNOWN');
        });

        $allRequests = $veraRequests->concat($pdRequests)
            ->concat($mskiRequests)
            ->concat($bankRequests)
            ->concat($umumRequests)
            ->concat($berkasGenerik)
            ->sortByDesc('created_at')
            ->values();

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allRequests->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedRequests = new LengthAwarePaginator(
            $currentItems,
            $allRequests->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get all active divisi for stats
        $divisis = Divisi::where('is_active', true)->orderBy('nama')->get();
        
        // Calculate stats per divisi
        $divisiStats = [];
        foreach ($divisis as $divisi) {
            $count = 0;
            $slug = strtolower($divisi->nama);
            
            // Count from legacy tables
            if ($slug === 'vera') $count = Vera::count();
            elseif ($slug === 'pd') $count = LayananPd::count();
            elseif ($slug === 'mski') $count = Mski::count();
            elseif ($slug === 'bank') $count = Bank::count();
            elseif ($slug === 'umum') $count = Umum::count();
            
            // Add count from berkas_layanans
            $count += BerkasLayanan::where('divisi_id', $divisi->id)->count();
            
            $divisiStats[$divisi->slug] = [
                'nama' => $divisi->nama,
                'count' => $count,
            ];
        }

        $tahunList = $allRequests->pluck('created_at')
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('staff.dashboard', compact(
            'divisiStats',
            'paginatedRequests',
            'allRequests',
            'tahunList',
            'divisis'
        ));
    }

    // ==================== BERKAS MASUK ====================
    public function index()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        // Legacy tables - set layanan_type for each
        $veraRequests = $divisi === 'VERA' ? Vera::latest()->get() : collect();
        $veraRequests->each(fn($r) => $r->layanan_type = 'vera');
        
        $pdRequests = $divisi === 'PD' ? LayananPd::latest()->get() : collect();
        $pdRequests->each(fn($r) => $r->layanan_type = 'pd');
        
        $mskiRequests = $divisi === 'MSKI' ? Mski::latest()->get() : collect();
        $mskiRequests->each(fn($r) => $r->layanan_type = 'mski');
        
        $bankRequests = $divisi === 'BANK' ? Bank::latest()->get() : collect();
        $bankRequests->each(fn($r) => $r->layanan_type = 'bank');
        
        $umumRequests = $divisi === 'UMUM' ? Umum::latest()->get() : collect();
        $umumRequests->each(fn($r) => $r->layanan_type = 'umum');

        // Berkas from berkas_layanans for this staff's divisi
        $divisiModel = Divisi::whereRaw('LOWER(nama) = ?', [strtolower(Auth::user()->divisi)])->first();
        $berkasGenerik = collect();
        if ($divisiModel) {
            $berkasGenerik = BerkasLayanan::where('divisi_id', $divisiModel->id)->latest()->get();
            $berkasGenerik->each(fn($r) => $r->layanan_type = 'generik');
        }

        $allRequests = $veraRequests->concat($pdRequests)
            ->concat($mskiRequests)
            ->concat($bankRequests)
            ->concat($umumRequests)
            ->concat($berkasGenerik)
            ->sortByDesc('created_at')
            ->values();

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
            'berkasGenerik',
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

        $requestData = null;

        // SELALU cek berkas_layanans dulu (karena form baru submit ke sini)
        if (strtolower($layanan_type) === 'generik') {
            $requestData = BerkasLayanan::find($id);
        } else {
            // Cek di berkas_layanans dulu
            $requestData = BerkasLayanan::find($id);
            
            // Jika tidak ditemukan, cari di tabel legacy
            if (!$requestData) {
                switch (strtolower($layanan_type)) {
                    case 'vera':
                        $requestData = Vera::find($id);
                        break;
                    case 'pd':
                        $requestData = LayananPd::find($id);
                        break;
                    case 'mski':
                        $requestData = Mski::find($id);
                        break;
                    case 'bank':
                        $requestData = Bank::find($id);
                        break;
                    case 'umum':
                        $requestData = Umum::find($id);
                        break;
                }
            }
        }

        if (!$requestData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $requestData->status = $request->status;
        
        // Assign staff yang memproses berkas
        $requestData->staff_id = Auth::id();

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
        $veraRequests->each(fn($r) => $r->layanan_type = 'vera');
        
        $pdRequests = $divisi === 'PD' ? LayananPd::where('status', 'diproses')->latest()->get() : collect();
        $pdRequests->each(fn($r) => $r->layanan_type = 'pd');
        
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'diproses')->latest()->get() : collect();
        $mskiRequests->each(fn($r) => $r->layanan_type = 'mski');
        
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'diproses')->latest()->get() : collect();
        $bankRequests->each(fn($r) => $r->layanan_type = 'bank');
        
        $umumRequests = $divisi === 'UMUM' ? Umum::where('status', 'diproses')->latest()->get() : collect();
        $umumRequests->each(fn($r) => $r->layanan_type = 'umum');

        // Berkas from berkas_layanans for this staff's divisi
        $divisiModel = Divisi::whereRaw('LOWER(nama) = ?', [strtolower(Auth::user()->divisi)])->first();
        $berkasGenerik = collect();
        if ($divisiModel) {
            $berkasGenerik = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->where('status', 'diproses')
                ->latest()->get();
            $berkasGenerik->each(fn($r) => $r->layanan_type = 'generik');
        }

        $allRequests = $veraRequests->concat($pdRequests)
            ->concat($mskiRequests)
            ->concat($bankRequests)
            ->concat($umumRequests)
            ->concat($berkasGenerik)
            ->sortByDesc('created_at')
            ->values();

        return view('staff.berkasproses', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests',
            'berkasGenerik',
            'allRequests'
        ));
    }

    // ==================== BERKAS SELESAI ====================
    public function berkasSelesai()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'selesai')->latest()->get() : collect();
        $veraRequests->each(fn($r) => $r->layanan_type = 'vera');
        
        $pdRequests = $divisi === 'PD' ? LayananPd::where('status', 'selesai')->latest()->get() : collect();
        $pdRequests->each(fn($r) => $r->layanan_type = 'pd');
        
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'selesai')->latest()->get() : collect();
        $mskiRequests->each(fn($r) => $r->layanan_type = 'mski');
        
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'selesai')->latest()->get() : collect();
        $bankRequests->each(fn($r) => $r->layanan_type = 'bank');
        
        $umumRequests = $divisi === 'UMUM' ? Umum::where('status', 'selesai')->latest()->get() : collect();
        $umumRequests->each(fn($r) => $r->layanan_type = 'umum');

        // Berkas from berkas_layanans for this staff's divisi
        $divisiModel = Divisi::whereRaw('LOWER(nama) = ?', [strtolower(Auth::user()->divisi)])->first();
        $berkasGenerik = collect();
        if ($divisiModel) {
            $berkasGenerik = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->where('status', 'selesai')
                ->latest()->get();
            $berkasGenerik->each(fn($r) => $r->layanan_type = 'generik');
        }

        $allRequests = $veraRequests->concat($pdRequests)
            ->concat($mskiRequests)
            ->concat($bankRequests)
            ->concat($umumRequests)
            ->concat($berkasGenerik)
            ->sortByDesc('created_at')
            ->values();

        return view('staff.berkasselesai', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests',
            'berkasGenerik',
            'allRequests'
        ));
    }

    // ==================== BERKAS DITOLAK ====================
    public function berkasDitolak()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'ditolak')->latest()->get() : collect();
        $veraRequests->each(fn($r) => $r->layanan_type = 'vera');
        
        $pdRequests = $divisi === 'PD' ? LayananPd::where('status', 'ditolak')->latest()->get() : collect();
        $pdRequests->each(fn($r) => $r->layanan_type = 'pd');
        
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'ditolak')->latest()->get() : collect();
        $mskiRequests->each(fn($r) => $r->layanan_type = 'mski');
        
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'ditolak')->latest()->get() : collect();
        $bankRequests->each(fn($r) => $r->layanan_type = 'bank');
        
        $umumRequests = $divisi === 'UMUM' ? Umum::where('status', 'ditolak')->latest()->get() : collect();
        $umumRequests->each(fn($r) => $r->layanan_type = 'umum');

        // Berkas from berkas_layanans for this staff's divisi
        $divisiModel = Divisi::whereRaw('LOWER(nama) = ?', [strtolower(Auth::user()->divisi)])->first();
        $berkasGenerik = collect();
        if ($divisiModel) {
            $berkasGenerik = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->where('status', 'ditolak')
                ->latest()->get();
            $berkasGenerik->each(fn($r) => $r->layanan_type = 'generik');
        }

        $allRequests = $veraRequests->concat($pdRequests)
            ->concat($mskiRequests)
            ->concat($bankRequests)
            ->concat($umumRequests)
            ->concat($berkasGenerik)
            ->sortByDesc('created_at')
            ->values();

        return view('staff.berkasditolak', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'umumRequests',
            'berkasGenerik',
            'allRequests'
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

        // SELALU cari di berkas_layanans dulu (karena form baru submit ke sini)
        $data = BerkasLayanan::find($id);
        $successMessage = 'Feedback berhasil disimpan.';

        // Jika tidak ditemukan, coba di tabel legacy berdasarkan divisi staff
        if (!$data) {
            $legacyDivisi = ['UMUM', 'BANK', 'VERA', 'PD', 'MSKI'];
            
            if (in_array($divisi, $legacyDivisi)) {
                switch ($divisi) {
                    case 'UMUM':
                        $data = Umum::find($id);
                        $successMessage = 'Feedback berhasil disimpan untuk layanan UMUM.';
                        break;
                    case 'BANK':
                        $data = Bank::find($id);
                        $successMessage = 'Feedback berhasil disimpan untuk layanan BANK.';
                        break;
                    case 'VERA':
                        $data = Vera::find($id);
                        $successMessage = 'Feedback berhasil disimpan untuk layanan VERA.';
                        break;
                    case 'PD':
                        $data = LayananPd::find($id);
                        $successMessage = 'Feedback berhasil disimpan untuk layanan PD.';
                        break;
                    case 'MSKI':
                        $data = Mski::find($id);
                        $successMessage = 'Feedback berhasil disimpan untuk layanan MSKI.';
                        break;
                }
            }
        }

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Simpan file jika ada, dengan format nama khusus
        if ($request->hasFile('feedback_file')) {
            $file = $request->file('feedback_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $today = now()->format('Ymd');
            $newFileName = "{$divisi}-{$today}-{$originalName}.{$extension}";

            $filePath = $file->storeAs('feedback', $newFileName, 'public');
            $data->feedback_file = $filePath;
        }

        $data->feedback = $validated['feedback'];
        $data->staff_id = Auth::id();
        $data->save();

        return redirect()->back()->with('success', $successMessage);
    }
}