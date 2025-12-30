<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umum;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UmumController extends Controller
{
    /**
     * Form pengajuan layanan umum
     */
    public function create()
    {
        $jenis_layanan = Layanan::where('layanan_type', 'Umum')
            ->where('is_active', true)
            ->pluck('jenis_layanan')
            ->toArray();

        return view('user.layanan-umum.create', [
            'jenis_layanan'    => $jenis_layanan,
            'userNip'          => Auth::user()->nip,
            'noBerkasPreview'  => 'UMUM-' . Carbon::now()->format('YmdHis')
        ]);
    }

    /**
     * Simpan pengajuan layanan umum
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_layanan' => 'required|string|exists:layanans,jenis_layanan',
            'keterangan'    => 'required|string',
            'file_upload'   => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
        ], [
            'file_upload.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, atau RAR',
        ]);

        // ==== Generate nomor berkas ====
        $today = Carbon::now()->format('Ymd'); // contoh: 20251226
        $urutan = Umum::whereDate('created_at', Carbon::today())->count() + 1;

        $noBerkas = 'UMUM-' . $today . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        // ==== File upload ====
        $file = $request->file('file_upload');

        // Ambil nama tanpa ekstensi
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Hapus jika user upload file yang sudah ada prefix UMUM-YYYYMMDD-XXX
        $originalName = preg_replace('/^UMUM-\d{8}-\d{3}-?/i', '', $originalName);

        // Bersihkan karakter aneh → ganti jadi _
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

        // Batasi panjang supaya aman
        $cleanName = Str::limit($cleanName, 80, '');

        // Ekstensi file
        $ext = $file->getClientOriginalExtension();

        // ==== Nama file final ====
        // Contoh: UMUM-20251226-003-Jurnal_Farah_Kohesi.pdf
        $newFileName = $noBerkas . '-' . $cleanName . '.' . $ext;

        // Simpan file ke storage/app/public/uploads/layanan
        $filePath = $file->storeAs('uploads/layanan', $newFileName, 'public');

        // ==== Simpan ke database ====
        Umum::create([
            'no_berkas'         => $noBerkas,
            'id_satker'         => Auth::user()->nip,
            'jenis_layanan'     => $request->jenis_layanan,
            'keterangan'        => $request->keterangan,
            'file_path'         => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'user_id'           => Auth::id(),
            'status'            => 'baru',   // pastikan kolomnya ada
        ]);

        return back()->with('success', 'Layanan Umum berhasil dikirim.');
    }
}
