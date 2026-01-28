<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BerkasLayanan;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        // Get all berkas from berkas_layanans table
        $allRequests = BerkasLayanan::with('divisi')->latest()->get();
        
        // Add layanan_type for each item
        $allRequests->each(function($r) {
            $r->layanan_type = strtoupper($r->divisi->nama ?? 'UNKNOWN');
        });

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
            $count = BerkasLayanan::where('divisi_id', $divisi->id)->count();
            
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
    /**
     * Menampilkan Halaman List Berkas Masuk
     * - Hanya menampilkan berkas sesuai Divisi Staff yang login.
     * - Filter otomatis berdasarkan nama divisi user.
     */
    public function index()
    {
        $staffDivisi = strtoupper(Auth::user()->divisi);

        // Cari ID Divisi yang sesuai dengan Staff ini
        $divisiModel = Divisi::whereRaw('UPPER(nama) = ?', [$staffDivisi])->first();
        
        $allRequests = collect();
        // Jika Staff punya divisi valid, ambil berkas milik divisi tersebut
        if ($divisiModel) {
            $allRequests = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->latest()
                ->get();
            $allRequests->each(fn($r) => $r->layanan_type = strtoupper($divisiModel->nama));
        }

        // Ambil list tahun untuk filter di UI
        $tahunList = $allRequests->pluck('created_at')
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('staff.berkas.index', compact(
            'allRequests',
            'tahunList'
        ));
    }

    /**
     * Memproses Perubahan Status Berkas (Terima / Tolak / Selesai)
     */
    public function updateStatus(Request $request, $id, $layanan_type)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|string|in:baru,diproses,selesai,ditolak',
            'alasan_penolakan' => 'nullable|string|max:255',
        ]);

        $requestData = BerkasLayanan::find($id);

        if (!$requestData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Update status di database
        $requestData->status = $request->status;
        
        // Catat ID Staff yang melakukan update ini (Audit Trail sederhana)
        $requestData->staff_id = Auth::id();

        // Jika ditolak, wajib simpan alasannya
        if ($request->status === 'ditolak') {
            $requestData->alasan_penolakan = $request->alasan_penolakan;
        } else {
            $requestData->alasan_penolakan = null; // Reset jika status berubah jadi diproses/selesai
        }

        $requestData->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    // ==================== BERKAS DIPROSES ====================
    public function berkasProses()
    {
        $staffDivisi = strtoupper(Auth::user()->divisi);

        // Find divisi model for this staff
        $divisiModel = Divisi::whereRaw('UPPER(nama) = ?', [$staffDivisi])->first();
        
        $allRequests = collect();
        if ($divisiModel) {
            $allRequests = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->where('status', 'diproses')
                ->latest()
                ->get();
            $allRequests->each(fn($r) => $r->layanan_type = strtoupper($divisiModel->nama));
        }

        return view('staff.berkas.proses', compact('allRequests'));
    }

    // ==================== BERKAS SELESAI ====================
    public function berkasSelesai()
    {
        $staffDivisi = strtoupper(Auth::user()->divisi);

        // Find divisi model for this staff
        $divisiModel = Divisi::whereRaw('UPPER(nama) = ?', [$staffDivisi])->first();
        
        $allRequests = collect();
        if ($divisiModel) {
            $allRequests = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->where('status', 'selesai')
                ->latest()
                ->get();
            $allRequests->each(fn($r) => $r->layanan_type = strtoupper($divisiModel->nama));
        }

        return view('staff.berkas.selesai', compact('allRequests'));
    }

    // ==================== BERKAS DITOLAK ====================
    public function berkasDitolak()
    {
        $staffDivisi = strtoupper(Auth::user()->divisi);

        // Find divisi model for this staff
        $divisiModel = Divisi::whereRaw('UPPER(nama) = ?', [$staffDivisi])->first();
        
        $allRequests = collect();
        if ($divisiModel) {
            $allRequests = BerkasLayanan::where('divisi_id', $divisiModel->id)
                ->where('status', 'ditolak')
                ->latest()
                ->get();
            $allRequests->each(fn($r) => $r->layanan_type = strtoupper($divisiModel->nama));
        }

        return view('staff.berkas.ditolak', compact('allRequests'));
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

        // Find in berkas_layanans
        $data = BerkasLayanan::find($id);

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

        return redirect()->back()->with('success', 'Feedback berhasil disimpan.');
    }
}